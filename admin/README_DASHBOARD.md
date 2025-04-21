# EA-RA Hardware Dashboard Implementation

This directory contains the implementation of the EA-RA Hardware dashboard, providing visualizations for:
- Sales trend analysis
- Expense category tracking
- Employee salary performance
- Product sales analysis
- Inventory status monitoring

## Setup Instructions

1. Import the database schemas (if not already done):
   ```sql
   mysql -u username -p databasename < ../database/expense_schema.sql
   mysql -u username -p databasename < ../database/inventory_schema.sql
   mysql -u username -p databasename < ../database/payroll_schema.sql
   mysql -u username -p databasename < ../database/sales_schema.sql
   ```

2. Import sample data for testing (optional):
   ```sql
   mysql -u username -p databasename < ../database/sample_data.sql
   ```

3. Ensure the dashboard API endpoint is accessible:
   - `/admin/pages/api/dashboard_data.php`
   - This API accepts a `timeRange` parameter with values: `today`, `week`, `month`, `year`

## Dashboard Features

### 1. Expense Category Visualization
- The doughnut chart shows expenses by category
- Helps to identify the most significant spending areas
- Color-coded for easy identification

### 2. Sales Trend Analysis
- Bar charts and line graphs display sales trends over time
- Shows best-selling and least-selling products
- Helps in making smarter restocking decisions

### 3. Inventory Monitoring
- Color-coded indicators show inventory levels:
  - Green: Normal stock
  - Yellow: Low stock (below reorder level)
  - Red: Out of stock
- Provides a clear visual of inventory status across categories

### 4. Employee Salary Performance
- Line graph shows individual salary trends
- Bar graph displays total payroll costs
- Helps identify employee performance trends

### 5. Sales Revenue Overtime
- Line chart shows sales revenue trends over different time periods
- Includes comparisons with previous periods 
- Includes moving average trendline
- Helps determine which days, months, or years have higher sales

## Time Range Filtering

The dashboard includes a time range selector that allows filtering data by:
- Today
- This Week
- This Month
- This Year

## Technical Details

- The dashboard uses Chart.js for visualizations
- Data is fetched from the server using AJAX calls
- The dashboard automatically refreshes when changing time ranges
- All monetary values are displayed in Philippine Peso (₱)

## Troubleshooting

If charts are not loading:
1. Check browser console for JavaScript errors
2. Verify API endpoint is accessible
3. Ensure database connection is working
4. Check PHP error logs for any server-side issues 