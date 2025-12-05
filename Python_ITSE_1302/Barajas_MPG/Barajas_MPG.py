num_tanks = 0
mpg = 0
average_mpg = 0
total_mpg = 0
miles_traveled = 0
gallons_used = 0

print("Enter gallons used (Input -1 to end):")
gallons_used = float(input())
while(gallons_used != -1):
    print("Enter miles traveled:")
    miles_traveled = int(input())
    mpg = miles_traveled / gallons_used
    print(f"The miles/gallon for this tank is {mpg}")
    total_mpg += mpg
    num_tanks += 1
    print("\nEnter gallons used (Input -1 to end):")
    gallons_used = float(input())
average_mpg = total_mpg / num_tanks
print(f"Average miles per gallon per tankful: {average_mpg:.2f}")
if(average_mpg < 22):
    print("Poor gas mileage.")