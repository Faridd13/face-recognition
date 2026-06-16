import requests

try:
    response = requests.get('http://localhost:5000/api/metrics')
    print(f"Status code: {response.status_code}")
    print(f"Response: {response.json()}")
except Exception as e:
    import traceback
    print("ERROR calling /api/metrics:")
    print(traceback.format_exc())
