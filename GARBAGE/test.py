from pypylon import pylon
import cv2

IP_ADDRESS = '10.10.3.11'  # Replace with your camera's IP address

# Get the transport layer factory.
tl_factory = pylon.TlFactory.GetInstance()

# Create an empty device info list.
devices = tl_factory.EnumerateDevices()

target_device = None
for device in devices:
    if device.GetIpAddress() == IP_ADDRESS:
        target_device = device
        break

if not target_device:
    print("Camera with the specified IP not found.")
    exit()

camera = pylon.InstantCamera(tl_factory.CreateDevice(target_device))
camera.Open()

num_images = 1
camera.GainAuto.SetValue("Off")
#camera.ExposureTime.SetValue(5000)  # Adjust based on light conditions

camera.StartGrabbingMax(num_images)

image_count = 0
while camera.IsGrabbing():
    grab_result = camera.RetrieveResult(5000, pylon.TimeoutHandling_ThrowException)
    if grab_result.GrabSucceeded():
        image = grab_result.Array
        cv2.imwrite(f"output_from_ip_{image_count}.jpg", image)
        image_count += 1

camera.Close()
