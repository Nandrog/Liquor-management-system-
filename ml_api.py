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
