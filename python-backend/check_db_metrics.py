from config import get_db_connection

conn = get_db_connection()
if conn:
    try:
        cursor = conn.cursor(dictionary=True)
        
        print("=== EXPERIMENT LOGS ===")
        cursor.execute("SELECT * FROM experiment_logs")
        logs = cursor.fetchall()
        print(f"Found {len(logs)} logs")
        for log in logs:
            print(log)
            
        print("\n=== EVALUATION METRICS ===")
        cursor.execute("SELECT * FROM evaluation_metrics")
        metrics = cursor.fetchall()
        print(f"Found {len(metrics)} metrics records")
        for m in metrics:
            print(m)
            
        cursor.close()
        conn.close()
    except Exception as e:
        import traceback
        print("ERROR:")
        print(traceback.format_exc())
