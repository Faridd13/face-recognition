import cv2
import os
from face_recognition_system import FaceRecognitionSystem

face_system = FaceRecognitionSystem()

# Load test image from dataset
test_img_path = os.path.join('dataset', '1', 'student_1_cond_1_1.jpg')

if not os.path.exists(test_img_path):
    print(f"Test image not found: {test_img_path}")
    exit()

print(f"Testing recognition with image: {test_img_path}")

# Load image
img = cv2.imread(test_img_path)
if img is None:
    print("Could not read image")
    exit()

# Load model
if face_system.load_model():
    print("Model loaded successfully!")
else:
    print("Model not found! Training now...")
    face_system.train_model()
    face_system.load_model()

# Recognize
results = face_system.recognize_face(img)
print("\nRecognition Results:")
print("-------------------")
for res in results:
    print(f"Student ID: {res['student_id']}")
    print(f"Student Name: {res['student_name']}")
    print(f"Confidence: {res['confidence']}%")
    print(f"Latency: {res['latency']}ms")
