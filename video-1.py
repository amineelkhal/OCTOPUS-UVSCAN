import cv2
import time

# MJPEG Stream URL
stream_url = "http://10.10.3.12:9901/livepic.mjpeg?id=92"

# Open the MJPEG stream using OpenCV
cap = cv2.VideoCapture(stream_url)

# Define the codec and create VideoWriter object to save the video
fourcc = cv2.VideoWriter_fourcc(*'XVID')
out = cv2.VideoWriter('output.avi', fourcc, 20.0, (int(cap.get(3)), int(cap.get(4))))

start_time = time.time()

while True:
    ret, frame = cap.read()
    if ret:
        out.write(frame)
    if time.time() - start_time > 3:
        break

# Release the video objects
cap.release()
out.release()
cv2.destroyAllWindows()
