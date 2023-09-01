from flask import Flask, render_template, jsonify
from pypylon import pylon
import cv2
import time
import numpy as np

app = Flask(__name__, template_folder="templates")

@app.route('/')
def index():
    return render_template('indexv4.html')

@app.route('/grab_images', methods=['POST'])
def grab_images():
    IP_ADDRESS = '10.10.3.11'
    tl_factory = pylon.TlFactory.GetInstance()
    devices = tl_factory.EnumerateDevices()
    target_device = next((device for device in devices if device.GetIpAddress() == IP_ADDRESS), None)

    if not target_device:
        return jsonify({'status': 'error', 'message': 'Camera with the specified IP not found.'})

    camera = pylon.InstantCamera(tl_factory.CreateDevice(target_device))
    camera.Open()
    camera.StartGrabbing(pylon.GrabStrategy_LatestImageOnly)

    end_time = time.time() + 2
    images = []

    while time.time() < end_time:
        if camera.IsGrabbing():
            grab_result = camera.RetrieveResult(5000, pylon.TimeoutHandling_ThrowException)
            if grab_result.GrabSucceeded():
                image = cv2.cvtColor(grab_result.Array, cv2.COLOR_BAYER_BG2BGR)
                aspect_ratio = image.shape[1] / image.shape[0]
                dim = (1000, int(1000 / aspect_ratio))
                resized_image = cv2.resize(image, dim, interpolation=cv2.INTER_AREA)
                rotated_image = cv2.rotate(resized_image, cv2.ROTATE_90_CLOCKWISE)

                # Stretching the right edge by 10 pixels
                if len(images) < end_time - time.time() - 1:  # Except the last image
                    stretched_edge = rotated_image[:, -10:]
                    stretched_image = np.hstack((rotated_image, stretched_edge))
                else:
                    stretched_image = rotated_image

                images.append(stretched_image)

    camera.Close()

    if images:
        final_image = np.hstack(images)
        final_image_path = "static/final_captured_image.jpg"
        cv2.imwrite(final_image_path, final_image)
        return jsonify({'status': 'success', 'message': 'Images captured and assembled successfully.', 'image': final_image_path})
    else:
        return jsonify({'status': 'error', 'message': 'Failed to capture images.'})

if __name__ == "__main__":
    app.run(debug=True)
