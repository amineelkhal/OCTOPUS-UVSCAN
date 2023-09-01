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
camera.Close()