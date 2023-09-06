import cv2
import numpy as np

def resize_with_aspect_ratio(image, desired_height):
    aspect_ratio = float(image.shape[1]) / float(image.shape[0])
    width = int(aspect_ratio * desired_height)
    return cv2.resize(image, (width, desired_height))

# Load the two images
image1_original = cv2.imread("scan4.jpg")
image2_original = cv2.imread("scan3.jpg")

# Ensure the images are of the same size and type
if image1_original.shape != image2_original.shape:
    print("The images have different sizes or number of channels")
    exit()

# Create a copy of image1_original for analysis so that the original remains untouched
image1_analysis = image1_original.copy()

# Compute the absolute difference between the images
difference = cv2.absdiff(image1_analysis, image2_original)

# Convert the difference to grayscale for thresholding
gray_difference = cv2.cvtColor(difference, cv2.COLOR_BGR2GRAY)

# Threshold the grayscale difference
_, thresholded_difference = cv2.threshold(gray_difference, 50, 255, cv2.THRESH_BINARY)

# Apply morphological operations to reduce noise
kernel = np.ones((3,3),np.uint8)
thresholded_difference = cv2.morphologyEx(thresholded_difference, cv2.MORPH_OPEN, kernel, iterations = 2)
thresholded_difference = cv2.morphologyEx(thresholded_difference, cv2.MORPH_CLOSE, kernel, iterations = 2)

# Find contours of the difference regions
contours, _ = cv2.findContours(thresholded_difference, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)

# Filter out small contours and draw rectangles around differing regions on the analysis copy
min_contour_area = 100
for contour in contours:
    if cv2.contourArea(contour) > min_contour_area:
        (x, y, w, h) = cv2.boundingRect(contour)
        cv2.rectangle(image1_analysis, (x, y), (x + w, y + h), (0, 255, 0), 2)

# Resize the original images for miniatures
miniature_height = 200  
miniature1 = resize_with_aspect_ratio(image1_original, miniature_height)  # Using untouched original for miniature
miniature2 = resize_with_aspect_ratio(image2_original, miniature_height)

# Horizontally concatenate the miniatures
miniatures = np.hstack((miniature1, miniature2))

# Resize the analyzed image for the final display
resized_image1 = resize_with_aspect_ratio(image1_analysis, 500)

# Ensure the width of the miniatures matches the width of the resized_image1
if miniatures.shape[1] < resized_image1.shape[1]:
    diff = resized_image1.shape[1] - miniatures.shape[1]
    padding = np.zeros((miniatures.shape[0], diff, 3), dtype=np.uint8)
    miniatures = np.hstack((miniatures, padding))

# Vertically concatenate the miniatures on top of the analyzed image
final_image = np.vstack((miniatures, resized_image1))

cv2.imshow("Differences", final_image)
cv2.waitKey(0)
cv2.destroyAllWindows()
