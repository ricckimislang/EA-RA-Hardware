# Hardware Store Payroll System

A simple payroll processing system for hardware store employees based on attendance records and employee hourly rates.

## System Overview

The payroll system automates the calculation of employee salaries based on their attendance records and hourly pay rates. All employees are now paid on an hourly basis, with the system calculating hourly rates from monthly salaries and tracking total hours worked.

## Features

- **Pay Period Management**: Create and track pay periods
- **Automatic Payroll Calculation**: Calculate gross pay based on employee attendance 
- **Deduction Processing**: Apply standard Philippine deductions (SSS, PhilHealth, Pag-IBIG, Tax)
- **Payroll Reports**: Generate comprehensive payroll reports per pay period
- **Individual Pay Slips**: Create and print individual employee pay slips

## Payroll Process Flow

1. **Define Pay Period**: System automatically selects the most recent 15-day period
2. **Gather Attendance Records**: System pulls attendance data for all employees
3. **Calculate Hourly Rate**: Convert monthly salary to hourly rate using the formula:  
   **Hourly Rate = Monthly Salary / (22 work days × 8 hours)**
4. **Calculate Gross Pay**: Apply hourly rate to total hours worked:  
   **Gross Pay = Hourly Rate × Total Hours**
5. **Apply Deductions**: Calculate and subtract government mandated deductions
6. **Generate Reports**: Produce both summary and individual payroll records

## Pay Calculation Logic

### Hourly Rate Calculation
- Monthly salaries are converted to hourly rates using:  
  **Hourly Rate = Monthly Salary / (22 work days × 8 hours)**
- For example, a monthly salary of ₱10,000 converts to:  
  **₱10,000 / (22 × 8) = ₱56.82 per hour**

### Hours Tracking
- Hours are tracked as whole numbers (integer values)
- System records total hours from the attendance records
- For each employee, gross pay = hourly rate × total hours worked

### Attendance Handling
- **Present**: Full day (typically 9 hours)
- **Late**: Full day with reduced hours (typically 8 hours)
- **Half-day**: Partial day (typically 4 hours)
- **Absent**: No hours recorded (0 hours)

### Deduction Rates
- SSS: 5% of gross pay
- PhilHealth: 2.5% of gross pay
- Pag-IBIG: 2% of gross pay
- Withholding Tax: Fixed amount (₱200)

## Technical Implementation

The system consists of:

- **PHP Backend**: Handles database operations and calculations
- **API Endpoints**: Process payroll, retrieve reports, generate pay slips
- **JavaScript Frontend**: Provides interactive user interface
- **Responsive UI**: Bootstrap-based interface for easy operation

## Usage Instructions

1. Navigate to the Payroll Management page
2. Click "Process Payroll" to generate payroll for the most recent 15-day period
3. Use the "View Payroll Reports" section to see results
4. Generate individual pay slips as needed

## Future Enhancements

- Overtime pay calculation
- Customizable deduction rates
- Leave management integration
- Advanced reporting features
- Minute-level time tracking (for partial hours) 