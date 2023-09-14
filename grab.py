import argparse
from pypylon import pylon
import cv2
import time
import numpy as np
from datetime import datetime

# Parse the command-line arguments
parser = argparse.ArgumentParser(description="Grab images from camera.")
parser.add_argument('--contrast', type=float, default=1.0)
parser.add_argument('--brightness', type=int, default=0)
parser.add_argument('--hist_eq_intensity', type=float, default=0.5)
parser.add_argument('--picturename', type=str, default='default_name')
parser.add_argument('--ip_address', type=str, default='10.10.3.11')
parser.add_argument('--duration', type=int, default=3, help='Time duration for grabbing in seconds')
parser.add_argument('--crop', type=int, default=3, help='Pixels to crop from the right')
args = parser.parse_args()

def grab_images():
    contrast = args.contrast
    brightness = args.brightness
    hist_eq_intensity = args.hist_eq_intensity
    picture_name = args.picturename
    IP_ADDRESS = args.ip_address
    duration = args.duration
    crop_value = args.crop

    tl_factory = pylon.TlFactory.GetInstance()
    devices = tl_factory.EnumerateDevices()
    target_device = next((device for device in devices if device.GetIpAddress() == IP_ADDRESS), None)

    if not target_device:
        return {'status': 'error', 'message': f'Camera with the specified IP not found: {IP_ADDRESS}'}

    camera = pylon.InstantCamera(tl_factory.CreateDevice(target_device))
    camera.Open()
    camera.StartGrabbing(pylon.GrabStrategy_LatestImageOnly)

    end_time = time.time() + duration
    images = []

    while time.time() < end_time:
        if camera.IsGrabbing():
            grab_result = camera.RetrieveResult(5000, pylon.TimeoutHandling_ThrowException)
            if grab_result.GrabSucceeded():
                image = cv2.cvtColor(grab_result.Array, cv2.COLOR_BAYER_BG2BGR)
                image = cv2.convertScaleAbs(image, alpha=contrast, beta=brightness)
                aspect_ratio = image.shape[1] / image.shape[0]
                dim = (1800, int(1800 / aspect_ratio))
                resized_image = cv2.resize(image, dim, interpolation=cv2.INTER_AREA)
                rotated_image = cv2.rotate(resized_image, cv2.ROTATE_90_CLOCKWISE)
                cropped_image = rotated_image[:, :-crop_value]  # Cropping based on the user input
                img_yuv = cv2.cvtColor(cropped_image, cv2.COLOR_BGR2YUV)
                img_yuv[:,:,0] = cv2.equalizeHist(img_yuv[:,:,0])
                equalized_image = cv2.cvtColor(img_yuv, cv2.COLOR_YUV2BGR)
                final_image = cv2.addWeighted(cropped_image, 1 - hist_eq_intensity, equalized_image, hist_eq_intensity, 0)
                images.append(final_image)

    camera.Close()

    if images:
        final_image = np.hstack(images)
        final_image_path = f"assets/scans/{picture_name}"
        cv2.imwrite(final_image_path, final_image)
        return {'status': 'success', 'message': 'Images captured and assembled.', 'image': final_image_path}
    else:
        return {'status': 'error', 'message': 'Failed to capture images.'}

if __name__ == "__main__":
    result = grab_images()
    print(result)
