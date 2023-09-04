import cv2
from pypylon import pylon

# Setup Basler Camera with IP address
ip_address = '10.10.3.11'
info = pylon.DeviceInfo()
info.SetPropertyValue("IpAddress", ip_address)
camera = pylon.InstantCamera(pylon.TlFactory.GetInstance().CreateDevice(info))
camera.Open()

# Camera settings
camera.GainAuto.SetValue("Off")
#camera.ExposureTime.SetValue(5000)  # Adjust based on light conditions
camera.AcquisitionMode.SetValue("SingleFrame")

def capture_image():
    camera.StartGrabbing(pylon.GrabStrategy_LatestImageOnly)
    converter = pylon.ImageFormatConverter()

    # Convert the grabbed buffer to an OpenCV compatible image
    converter.OutputPixelFormat = pylon.PixelType_BGR8packed
    converter.OutputBitAlignment = pylon.OutputBitAlignment_MsbAligned

    grab_result = camera.RetrieveResult(5000, pylon.TimeoutHandling_ThrowException)
    image = converter.Convert(grab_result)
    img = image.GetArray()
    return img

def process_image(img):
    # Image Processing using Canny edge detector as a simple example
    edges = cv2.Canny(img, 100, 200)
    return edges

if __name__ == '__main__':
    raw_img = capture_image()
    processed_img = process_image(raw_img)

    # Save the images
    cv2.imwrite('raw_image.jpg', raw_img)
    cv2.imwrite('processed_image.jpg', processed_img)

    #cv2.imshow('Processed Image', processed_img)
    #cv2.waitKey(0)
    #cv2.destroyAllWindows()
