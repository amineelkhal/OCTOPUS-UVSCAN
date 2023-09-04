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

# Grab a single image
camera.StartGrabbingMax(1)
with camera.RetrieveResult(5000) as result:
    image = result.Array
    cv2.imwrite("output_from_aca2000.jpg", image)

camera.Close()
