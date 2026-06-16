
import requests
import base64
import os

# Test image path
test_img_path = os.path.join('dataset', '1', 'student_1_cond_1_1.jpg')

if not os.path.exists(test_img_path):
    print(f"Test image not found: {test_img_path}")
    exit()

# Read and encode image
with open(test_img_path, 'rb') as img_file:
    img_bytes = img_file.read()
    img_base64 = base64.b64encode(img_bytes).decode('utf-8')
    data_url = f"data:image/jpeg;base64,{img_base64}"

# Send to API
url = "http://127.0.0.1:5000/api/recognize"
payload = {
    "image": data_url,
    "light_condition": "terang",
    "face_angle": "frontal",
    "distance_condition": "dekat"
}

response = requests.post(url, json=payload)
print(f"Status: {response.status_code}")
print(f"Response: {response.json()}")
