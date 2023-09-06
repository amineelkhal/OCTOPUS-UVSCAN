import cv2
import numpy as np
import matplotlib.pyplot as plt

# Load the image and convert to grayscale
image = cv2.imread("scan.jpg", cv2.IMREAD_GRAYSCALE)

# Apply Gaussian blurring
blurred_image = cv2.GaussianBlur(image, (3, 3), 0)

# Compute the x and y gradients using Sobel operator
gradient_x = cv2.Sobel(blurred_image, cv2.CV_64F, 1, 0, ksize=3)
gradient_y = cv2.Sobel(blurred_image, cv2.CV_64F, 0, 1, ksize=3)

# Compute the magnitude and angle of the gradient
magnitude, angle = cv2.cartToPolar(gradient_x, gradient_y)

# Visualize the results
plt.figure(figsize=(10, 10))

plt.subplot(2, 2, 1)
plt.imshow(image, cmap='gray')
plt.title("Original Image")

plt.subplot(2, 2, 2)
plt.imshow(magnitude, cmap='gray')
plt.title("Gradient Magnitude")

plt.subplot(2, 2, 3)
plt.imshow(gradient_x, cmap='gray')
plt.title("Gradient X")

plt.subplot(2, 2, 4)
plt.imshow(gradient_y, cmap='gray')
plt.title("Gradient Y")

plt.tight_layout()
plt.show()
