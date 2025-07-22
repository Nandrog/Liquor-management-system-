import mysql.connector
from flask import Flask, jsonify
from flask_cors import CORS
import pandas as pd

app = Flask(__name__)
CORS(app)

# Define DB connection
def get_db_connection():
    return mysql.connector.connect(
        host='localhost',
        user='your_mysql_user',
        password='your_mysql_password',
        database='your_database_name'
    )

@app.route('/api/forecast')
def forecast():
    conn = get_db_connection()
    cursor = conn.cursor(dictionary=True)

    # Fetch created_at and total_amount from orders table
    cursor.execute("SELECT created_at, total_amount FROM orders ORDER BY created_at ASC")
    rows = cursor.fetchall()

    conn.close()

    # Convert to DataFrame
    df = pd.DataFrame(rows)
    df['created_at'] = pd.to_datetime(df['created_at'])
    df.set_index('created_at', inplace=True)

    # Group by week and sum sales
    weekly_sales = df.resample('W-MON')['total_amount'].sum()

    # Forecast using ARIMA
    from statsmodels.tsa.arima.model import ARIMA
    model = ARIMA(weekly_sales.values, order=(2, 1, 2)).fit()
    forecast = model.forecast(steps=3).tolist()

    return jsonify({
        'weeks': weekly_sales.index.strftime('%Y-%m-%d').tolist(),
        'weekly_sales': weekly_sales.round(2).tolist(),
        'predicted_sales': [round(val, 2) for val in forecast],
        'efficiency': 96.5,
        'fulfillment_days': 4.3
    })

@app.route('/api/segments')
def segments():
    import numpy as np
    import pandas as pd
    from sklearn.cluster import KMeans

    df = pd.DataFrame({
        'customer_id': range(1, 201),
        'frequency': np.random.randint(1, 20, 200),
        'monetary': np.random.uniform(50, 5000, 200)
    })

    kmeans = KMeans(n_clusters=3, n_init='auto')
    df['segment'] = kmeans.fit_predict(df[['frequency', 'monetary']])

    segments = {
        str(seg): df[df['segment'] == seg][['customer_id', 'frequency', 'monetary']].to_dict(orient='records')
        for seg in df['segment'].unique()
    }

    centroids = kmeans.cluster_centers_.tolist()

    ranges = {
        str(seg): {
            'frequency_range': [
                int(df[df['segment'] == seg]['frequency'].min()),
                int(df[df['segment'] == seg]['frequency'].max())
            ],
            'monetary_range': [
                round(df[df['segment'] == seg]['monetary'].min(), 2),
                round(df[df['segment'] == seg]['monetary'].max(), 2)
            ]
        }
        for seg in df['segment'].unique()
    }

    print("Returning full segments structure")

    return jsonify({
        'segments': segments,
        'centroids': centroids,
        'ranges': ranges
    })

if __name__ == '__main__':
    app.run(debug=True, port=5000)
