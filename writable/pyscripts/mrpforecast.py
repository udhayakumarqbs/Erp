import pandas as pd
import numpy as np
import json
import sys
from statsmodels.tsa.holtwinters import SimpleExpSmoothing

def load_data_from_file(file_path):
    try:
        with open(file_path, 'r') as file:
            data = json.load(file)
        return data
    except Exception as e:
        print("Error occurred while loading data from file:", e)
        sys.exit(1)

def preprocess_data(data):
    try:
        df = pd.DataFrame(data)
        df['timestamp'] = pd.to_datetime(df['timestamp'])
        df['quantity'] = pd.to_numeric(df['quantity'], errors='coerce')
        df.set_index('timestamp', inplace=True)
        return df
    except Exception as e:
        print("Error occurred during data preprocessing:", e)
        sys.exit(1)

def fit_and_forecast(df, n_weeks):
    try:
        quantity_series = df['quantity']
        model = SimpleExpSmoothing(quantity_series)
        fit_model = model.fit()
        forecast = fit_model.forecast(n_weeks)

        # Prepare the output as a dictionary
        output_data = {"fitted_values": fit_model.fittedvalues.tolist(), "timestamps": df.index.astype(str).tolist()}

        # Print the output directly
        print(json.dumps(output_data))
    except Exception as e:
        print("Error occurred during model fitting and forecasting:", e)
        sys.exit(1)

if __name__ == "__main__":
    # Check if the correct number of arguments is provided
    if len(sys.argv) != 2:
        print("Usage: python mrpforecast.py input_file_path")
        sys.exit(1)

    # Load data from file
    input_file_path = sys.argv[1]
    data = load_data_from_file(input_file_path)

    # Preprocess the data
    df = preprocess_data(data)

    # Forecast for the next n weeks (adjust n as needed)
    n_weeks = 4
    fit_and_forecast(df, n_weeks)
    
