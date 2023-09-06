import cv2
import numpy as np
import matplotlib.pyplot as plt
from scipy import ndimage

# Load the image and convert to grayscale
image = cv2.imread("scan.jpg", cv2.IMREAD_GRAYSCALE)

# Apply Gaussian blurring
blurred_image = cv2.GaussianBlur(image, (3, 3), 0)

# Compute the x and y gradients using Sobel operator
gradient_x = cv2.Sobel(blurred_image.astype(np.float64), cv2.CV_64F, 1, 0, ksize=3)
gradient_y = cv2.Sobel(blurred_image.astype(np.float64), cv2.CV_64F, 0, 1, ksize=3)

# Poisson reconstruction (this is a basic way and there are more accurate methods)
divergence = np.fft.fftshift(np.fft.fft2(gradient_x + gradient_y))
depth = np.fft.ifft2(divergence).real

# Normalize the depth values to be between 0 and 255 for visualization
depth = cv2.normalize(depth, None, 0, 255, cv2.NORM_MINMAX)

# 3D Visualization of the depth
X, Y = np.meshgrid(np.arange(depth.shape[1]), np.arange(depth.shape[0]))
fig = plt.figure(figsize=(10, 10))
ax = fig.add_subplot(111, projection='3d')
ax.plot_surface(X, -Y, -depth, cmap='viridis')
plt.show()
