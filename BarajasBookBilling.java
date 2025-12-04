import java.util.Scanner;

public class BarajasBookBilling {
    public static void main(String[] args) {
        double itemPrice;
        int itemQuantity;
        double coupon;

        Scanner scan = new Scanner(System.in);
        Billing Book = new Billing();

        System.out.println("Input price of Book:");
        itemPrice = scan.nextDouble();
        System.out.printf("$%.2f\n", Book.computeBill(itemPrice));

        System.out.println("Input quantities of Book to order:");
        itemQuantity = scan.nextInt();
        System.out.printf("$%.2f\n", Book.computeBill(itemPrice, itemQuantity));

        System.out.println("Input coupon (amount of dollars off):");
        coupon = scan.nextDouble();
        System.out.printf("$%.2f\n", Book.computeBill(itemPrice, itemQuantity, coupon));

    }

    public static class Billing{

        //8.25% Tax added to the bill.
        final double TAX = 1.0825;

        public double computeBill(double price) {
            double total;
            total = price * TAX;
            return total;
        }
        public double computeBill(double price, int quant) {
            double total;
            total = quant * price * TAX;
            return total;
        }
        public double computeBill(double price, int quant, double couponVal) {
            double total;
            total = (quant * price - couponVal) * TAX;
            return total;
        }

    }

    //Josiah Barajas, 3/9/25, Lab#2 Overloading Methods
}
