import java.util.*;

public class ApartmentsLinkedList {
    public static void main(String[] args) {
        Scanner scan = new Scanner(System.in);
        Scanner numScan = new Scanner(System.in);
        LinkedList<Apartment> yourApartments = new LinkedList<Apartment>();
        ListIterator<Apartment> iter = yourApartments.listIterator();

        int apartmentNum, rooms;
        double rent;
        String address;

        System.out.println("This Program allow you to input a list of apartments");
        System.out.println("Including: Apartment Number, Address, Number of Bedrooms, and Rent\n");
        System.out.println("Would you like to enter an Apartment? y/n");

        String sentVal = scan.nextLine();
        sentVal.toLowerCase();

        while(sentVal.equals("y")) {
            System.out.println("Please input the Apartment Number:");
            apartmentNum = numScan.nextInt();

            System.out.println("Please input Apartment " + apartmentNum + "'s Street Address:");
            address = scan.nextLine();

            System.out.println("Please input the Number of Bedrooms in Apartment " + apartmentNum + ":");
            rooms = numScan.nextInt();

            System.out.println("Please input the Monthly Rent for Apartment " + apartmentNum + ":");
            rent = numScan.nextDouble();

            yourApartments.add(new Apartment(apartmentNum, address, rooms, rent));

            System.out.println("\nWould you like to enter an Apartment? y/n");
            sentVal = scan.nextLine();
            sentVal.toLowerCase();
        }

        //Sorts the LinkedList
        if(yourApartments.size() > 1) {
            int maxIndex = 0;
            Apartment temp;
            for(int i = 0; i < yourApartments.size(); i++) {
                for(int j = 0; j < yourApartments.size() - i - 1; j++) {
                    if(yourApartments.get(maxIndex).compareTo(yourApartments.get(j)) == -1) {
                        maxIndex = j;
                    }
                }
                temp = yourApartments.get(yourApartments.size() - i - 1);
                yourApartments.set(yourApartments.size() - i - 1,  yourApartments.get(maxIndex));
                yourApartments.set(maxIndex, temp);
            }
        }

        //Outputs the Linked List
        iter = yourApartments.listIterator(0);
        System.out.println("\nYour Apartments:");
        while(iter.hasNext()){
            Apartment display = iter.next();
            System.out.println(display.toString());
        }
    }
}