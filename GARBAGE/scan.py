from pypylon import pylon
import random
import platform

tl_factory = pylon.TlFactory.GetInstance()

ip_address = '10.10.2.11'
factory = pylon.TlFactory.GetInstance()
ptl = factory.CreateTl('BaslerGigE')
empty_camera_info = ptl.CreateDeviceInfo()
empty_camera_info.SetPropertyValue('IpAddress', ip_address)
camera_device = factory.CreateDevice(empty_camera_info)
camera = pylon.InstantCamera(camera_device)
camera.Open()

num_img_to_save = 40
img = pylon.PylonImage()

# demonstrate some feature access
camera.StartGrabbing()

for i in range(num_img_to_save):
    with camera.RetrieveResult(300) as result:

        # Calling AttachGrabResultBuffer creates another reference to the
        # grab result buffer. This prevents the buffer's reuse for grabbing.
        img.AttachGrabResultBuffer(result)

        if platform.system() == 'Windows':
            # The JPEG format that is used here supports adjusting the image
            # quality (100 -> best quality, 0 -> poor quality).
            ipo = pylon.ImagePersistenceOptions()
            quality = i
            ipo.SetQuality(quality)

            filename = "scans/%d.jpeg" % quality
            img.Save(pylon.ImageFileFormat_Jpeg, filename, ipo)
        else:
            filename = "scans/%d.png" % i
            img.Save(pylon.ImageFileFormat_Png, filename)

        # In order to make it possible to reuse the grab result for grabbing
        # again, we have to release the image (effectively emptying the
        # image object).
        img.Release()

camera.StopGrabbing()
camera.Close()