#include <stdio.h>


float commissionPayCalc();
float hourlyPayCalc(float hours);
float managerPayCalc();

//This program accepts an input for type of employee, calculates their pay, then outputs their pay and the company's total pay.
int main(void) {

    const int SIZE = 7;
    const float TAX = 0.08625;

    int employeeType;
    float companyTotalPay = 0;

    //These parallel arrays are used to store employee data so it can be all outputted at once at the end.
    int employeeID[SIZE];
    float employeeHoursWorked[SIZE];
    float employeeGrossPay[SIZE];
    float employeeTaxAmount[SIZE];
    float employeeNetPay[SIZE];

    for(int employee = 0; employee < SIZE; employee = employee + 1){

        printf("\n%d\) Input employee's ID:\n", employee+1);
        scanf("%f", &employeeID[employee]);

        printf("\nInput the type of employee. 1 are managers. 2 are hourly workers. 3 are commissioned workers:\n");
        scanf("%d", &employeeType);

        printf("\nInput hours employee worked this week:\n");
        scanf("%f", &employeeHoursWorked[employee]);

        switch (employeeType) {

            case 1:
                employeeGrossPay[employee] = managerPayCalc();
                break;

            case 2:
                employeeGrossPay[employee] = hourlyPayCalc(employeeHoursWorked[employee]);
                break;

            case 3:
                employeeGrossPay[employee] = commissionPayCalc();
                break;

        }

        //Calculates tax amount, net pay, and company's total pay.
        employeeTaxAmount[employee] = employeeGrossPay[employee] * TAX;
        employeeNetPay[employee] = employeeGrossPay[employee] - employeeTaxAmount[employee];

        companyTotalPay = companyTotalPay + employeeGrossPay[employee];
    }

    //Outputs data all at once
    for(int count = 0; count < SIZE; count = count + 1) {

        printf("\nEmployee %d\n", employeeID[count]);
        printf("Hours worked this week %.2f\n", employeeHoursWorked[count]);
        printf("Employee's Gross Pay %.2f\n", employeeGrossPay[count]);
        printf("Employee's Tax Amount %.2f\n", employeeTaxAmount[count]);
        printf("Employee's Net Pay %.2f\n", employeeNetPay[count]);
    }

    printf("Company's total pay %f", companyTotalPay);

    return 0;
}

//Accepts a the hourly wage, hours worked to calculate the workers pay. Every hour over 40, the worker gets paid 1.5 his hourly wage.
float hourlyPayCalc(float hours) {

    float hourlyWage;
    float overtimeHours;
    float weeklyPay = 0;

    printf("\nInput the employee's hourly wage:\n");
    scanf("%f", &hourlyWage);

    if(hours > 40) {

        weeklyPay = 40*hourlyWage;
        overtimeHours = hours - 40;
        weeklyPay = overtimeHours * (hourlyWage*1.5) + weeklyPay;

    }

    else {

        weeklyPay = hourlyWage * hours;

    }

    return weeklyPay;
}

//This function accepts the manager's yearly salary, and converts into a weekly salary.
float managerPayCalc(){

    float yearlySalary;
    float weeklyPay;

    printf("\nInput manager's yearly salary:\n");
    scanf("%f", &yearlySalary);

    weeklyPay = yearlySalary/52;

    return weeklyPay;
}

//Accepts the weekly sales made by the commission worker, and gives him 7.2% Cut of those sales + $375 pay for the commission.
float commissionPayCalc(){

    float cut = 0.072;
    float weeklySales;
    float weeklyPay = 375;

    printf("\nInput commission worker's weekly sales:\n");
    scanf("%f", &weeklySales);

    weeklyPay = weeklyPay + (weeklySales) * cut;

    return weeklyPay;
}
