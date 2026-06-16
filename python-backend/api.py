from flask import Flask, request, jsonify
from flask_cors import CORS
from face_recognition_system import FaceRecognitionSystem
import cv2
import numpy as np
import base64
from config import get_db_connection
from datetime import datetime, date
import logging

# Setup logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

app = Flask(__name__)
CORS(app)

face_system = FaceRecognitionSystem()

@app.route('/api/status', methods=['GET'])
def status():
    logger.info("GET /api/status request received")
    return jsonify({'status': 'ok', 'message': 'Face Recognition API is running'})

@app.route('/api/capture', methods=['POST'])
def capture():
    try:
        data = request.json
        student_id = data.get('student_id')
        condition_id = data.get('condition_id')
        images = data.get('images', [])
        
        if not student_id or not condition_id:
            return jsonify({'error': 'student_id and condition_id are required'}), 400
            
        captured = face_system.capture_face_data_from_images(student_id, condition_id, images)
        
        return jsonify({
            'status': 'success',
            'captured': captured,
            'message': f'Captured {captured} images'
        })
    except Exception as e:
        return jsonify({'error': str(e)}), 500

@app.route('/api/train', methods=['POST'])
def train():
    try:
        success = face_system.train_model()
        if success:
            return jsonify({'status': 'success', 'message': 'Model trained successfully'})
        else:
            return jsonify({'error': 'Failed to train model'}), 500
    except Exception as e:
        return jsonify({'error': str(e)}), 500

@app.route('/api/recognize', methods=['POST'])
def recognize():
    logger.info("POST /api/recognize request received")
    try:
        data = request.json
        logger.info(f"Received data keys: {list(data.keys())}")
        
        frame = None
        
        if 'image' in data:
            # Decode base64 data URL
            img_data = data['image']
            logger.info("Image data found in request")
            # Remove data URI scheme if present
            if ',' in img_data:
                img_data = img_data.split(',')[1]
            # Decode base64
            img_bytes = base64.b64decode(img_data)
            nparr = np.frombuffer(img_bytes, np.uint8)
            frame = cv2.imdecode(nparr, cv2.IMREAD_COLOR)
            logger.info(f"Image decoded successfully, shape: {frame.shape}")
        else:
            logger.info("No image data, using webcam capture")
            cap = cv2.VideoCapture(0)
            ret, frame = cap.read()
            cap.release()
            if not ret:
                return jsonify({'error': 'Failed to capture frame'}), 500
                
        conditions = {
            'light': data.get('light_condition', 'terang'),
            'angle': data.get('face_angle', 'frontal'),
            'distance': data.get('distance_condition', 'dekat')
        }
        
        if not face_system.load_model():
            logger.error("Model not trained yet!")
            return jsonify({'error': 'Model not trained yet'}), 400
            
        logger.info("Starting recognition...")
        results = face_system.recognize_face(frame, conditions)
        logger.info(f"Recognition results: {results}")
        
        return jsonify({
            'status': 'success',
            'results': results
        })
    except Exception as e:
        import traceback
        logger.error(f"Error in recognize endpoint: {str(e)}", exc_info=True)
        print(traceback.format_exc())
        return jsonify({'error': str(e)}), 500

@app.route('/api/attendance', methods=['POST'])
def mark_attendance():
    try:
        # Since we're handling the database insertion in Laravel now, we can just return success
        return jsonify({
            'status': 'success',
            'message': 'Attendance marked successfully'
        })
    except Exception as e:
        return jsonify({'error': str(e)}), 500

@app.route('/api/experiment/log', methods=['POST'])
def log_experiment():
    try:
        data = request.json
        
        # Buat conditions dictionary dari request
        conditions = {
            'light': data.get('light_condition', 'terang'),
            'angle': data.get('face_angle', 'frontal'),
            'distance': data.get('distance_condition', 'dekat')
        }
        
        face_system.save_experiment_log(
            data.get('student_id'),
            data.get('actual_identity'),
            data.get('predicted_identity'),
            data.get('confidence'),
            data.get('latency'),
            conditions,
            data.get('experiment_type')
        )
        
        return jsonify({'status': 'success', 'message': 'Experiment logged'})
    except Exception as e:
        return jsonify({'error': str(e)}), 500

@app.route('/api/metrics', methods=['GET'])
def get_metrics():
    try:
        metrics = face_system.calculate_metrics()
        if metrics:
            return jsonify({'status': 'success', 'metrics': metrics})
        else:
            return jsonify({'error': 'Failed to calculate metrics'}), 500
    except Exception as e:
        return jsonify({'error': str(e)}), 500


@app.route('/api/metrics/condition', methods=['GET'])
def get_metrics_by_condition():
    try:
        light_condition = request.args.get('light_condition')
        face_angle = request.args.get('face_angle')
        distance_condition = request.args.get('distance_condition')

        metrics = face_system.calculate_metrics_by_condition(
            light_condition=light_condition,
            face_angle=face_angle,
            distance_condition=distance_condition
        )

        if metrics:
            return jsonify({'status': 'success', 'metrics': metrics})
        else:
            return jsonify({'error': 'Failed to calculate metrics for condition'}), 500
    except Exception as e:
        return jsonify({'error': str(e)}), 500

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=True)
