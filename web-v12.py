from flask import Flask, render_template, jsonify, request
from flask_cors import CORS
from pypylon import pylon
import cv2
import time
import numpy as np
from datetime import datetime

app = Flask(__name__, template_folder="templates")
CORS(app)

@app.route('/')
def index():
    return render_template('indexv12.html')

@app.route('/grab_images', methods=['POST'])
def grab_images():
    contrast = float(request.form.get('contrast', 1.0))
    brightness = int(request.form.get('brightness', 0))
    hist_eq_intensity = float(request.form.get('hist_eq_intensity', 0.5))

    IP_ADDRESS = '10.10.3.11'
    tl_factory = pylon.TlFactory.GetInstance()
    devices = tl_factory.EnumerateDevices()
    target_device = next((device for device in devices if device.GetIpAddress() == IP_ADDRESS), None)

    if not target_device:
        return jsonify({'status': 'error', 'message': 'Camera with the specified IP not found.'})

    camera = pylon.InstantCamera(tl_factory.CreateDevice(target_device))
    camera.Open()
    camera.StartGrabbing(pylon.GrabStrategy_LatestImageOnly)

    end_time = time.time() + 4
    images = []

    while time.time() < end_time:
        if camera.IsGrabbing():
            grab_result = camera.RetrieveResult(5000, pylon.TimeoutHandling_ThrowException)
            if grab_result.GrabSucceeded():
                image = cv2.cvtColor(grab_result.Array, cv2.COLOR_BAYER_BG2BGR)
                
                # Adjust contrast and brightness
                image = cv2.convertScaleAbs(image, alpha=contrast, beta=brightness)
                
                aspect_ratio = image.shape[1] / image.shape[0]
                dim = (2000, int(2000 / aspect_ratio))
                resized_image = cv2.resize(image, dim, interpolation=cv2.INTER_AREA)
                
                rotated_image = cv2.rotate(resized_image, cv2.ROTATE_90_CLOCKWISE)
                cropped_image = rotated_image[:, :-3]  # Cropping 2 pixels from the right
                
                # Histogram Equalization based on user input
                img_yuv = cv2.cvtColor(cropped_image, cv2.COLOR_BGR2YUV)
                img_yuv[:,:,0] = cv2.equalizeHist(img_yuv[:,:,0])
                equalized_image = cv2.cvtColor(img_yuv, cv2.COLOR_YUV2BGR)
                final_image = cv2.addWeighted(cropped_image, 1 - hist_eq_intensity, equalized_image, hist_eq_intensity, 0)
                
                images.append(final_image)

    camera.Close()

    if images:
        final_image = np.hstack(images)
        timestamp_str = datetime.now().strftime("%Y%m%d_%H%M%S")
        final_image_path = f"static/final_captured_image_{timestamp_str}.jpg"
        cv2.imwrite(final_image_path, final_image)
        return jsonify({'status': 'success', 'message': 'Images captured and assembled.', 'image': final_image_path})
    else:
        return jsonify({'status': 'error', 'message': 'Failed to capture images.'})

if __name__ == "__main__":
    app.run(debug=True)
