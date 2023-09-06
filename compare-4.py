import cv2
import numpy as np
from skimage.metrics import structural_similarity as ssim

def preprocess_image(image_path):
    image = cv2.imread(image_path, cv2.IMREAD_GRAYSCALE)
    image = cv2.GaussianBlur(image, (5, 5), 0)
    image = cv2.resize(image, (800, 600))
    image = cv2.equalizeHist(image)  # Histogram Equalization
    return image

# Load images and preprocess
image1 = preprocess_image('scan5.jpg')
image2 = preprocess_image('scan6.jpg')

# Compute SSIM between two images
score, difference = ssim(image1, image2, full=True)
print(f"SSIM Score: {score}")

# Difference image needs to be rescaled to 8-bit so we can view it with OpenCV
difference = (difference * 255).astype("uint8")

# Threshold the difference image
_, thresholded_diff = cv2.threshold(difference, 70, 255, cv2.THRESH_BINARY_INV | cv2.THRESH_OTSU)

# Find contours in the thresholded difference
contours, _ = cv2.findContours(thresholded_diff.copy(), cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)

# Draw rectangles around differences
image_with_differences = cv2.cvtColor(image1, cv2.COLOR_GRAY2BGR)  # Convert to colored image for visualization
for contour in contours:
    (x, y, w, h) = cv2.boundingRect(contour)
    cv2.rectangle(image_with_differences, (x, y), (x + w, y + h), (0, 255, 0), 2)

# Visualize differences as a heatmap
heatmap = cv2.applyColorMap(50 - difference, cv2.COLORMAP_JET)

# Display
cv2.imshow("Original", image1)
#cv2.imshow("Compared", image2)
#cv2.imshow("Difference Heatmap", heatmap)
cv2.imshow("Differences Highlighted", image_with_differences)
cv2.waitKey(0)
cv2.destroyAllWindows()
