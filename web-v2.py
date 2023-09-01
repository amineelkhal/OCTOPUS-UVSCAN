from flask import Flask, render_template, jsonify
from pypylon import pylon
import cv2
import time

app = Flask(__name__, template_folder="templates")

@app.route('/')
def index():
    return render_template('index.html')

@app.route('/grab_images', methods=['POST'])
def grab_images():
    IP_ADDRESS = '10.10.3.11'  # Updated IP address

    tl_factory = pylon.TlFactory.GetInstance()
    devices = tl_factory.EnumerateDevices()

    target_device = next((device for device in devices if device.GetIpAddress() == IP_ADDRESS), None)

    if not target_device:
        return jsonify({'status': 'error', 'message': 'Camera with the specified IP not found.'})

    camera = pylon.InstantCamera(tl_factory.CreateDevice(target_device))
    camera.Open()
    camera.StartGrabbing(pylon.GrabStrategy_LatestImageOnly)

    end_time = time.time() + 5  # 5 seconds from now
    image_paths = []
    image_count = 0

    while time.time() < end_time:
        if camera.IsGrabbing():
            grab_result = camera.RetrieveResult(5000, pylon.TimeoutHandling_ThrowException)
            if grab_result.GrabSucceeded():
                image_filename = f"static/captured_image_{image_count}.jpg"
                image = grab_result.Array
                cv2.imwrite(image_filename, image)
                image_paths.append(image_filename)
                image_count += 1

    camera.Close()

    if image_paths:
        return jsonify({'status': 'success', 'message': 'Images captured successfully.', 'images': image_paths})
    else:
        return jsonify({'status': 'error', 'message': 'Failed to capture images.'})

if __name__ == "__main__":
    app.run(debug=True)
