public class Apartment {
    private int apartmentNum;
    private int numBedrooms;
    private String streetAddress;
    private double monthlyRent;

    public Apartment(int number, String address, int rooms, double rent){
        this.apartmentNum = number;
        this.numBedrooms = rooms;
        this.streetAddress = address;
        this.monthlyRent = rent;
    }

    public String toString() {
        String str = "Apartment Number:  " + this.apartmentNum + ", ";
        str += "Street Adress: " + this.streetAddress + ", ";
        str += "Number of Bedrooms: " + this.numBedrooms + ", ";
        str += "Monthly Rent: " + this.monthlyRent;
        return str;
    }

    public int compareTo(Apartment rival) {
        if(this.monthlyRent > rival.monthlyRent) {
            return 1;
        }
        else if(this.monthlyRent == rival.monthlyRent){
            return 0;
        }
        else {
            return -1;
        }
    }

}

