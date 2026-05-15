//import java.util.Collections;
import java.util.PriorityQueue;
import java.util.Iterator;


public class Barajas_SLO1 {
    public static void main(String[] args) {
        PriorityQueue<String> nameQueue1 = new PriorityQueue();
        PriorityQueue<String> nameQueue2 = new PriorityQueue();

        nameQueue1.add("George");
        nameQueue1.add("Jim");
        nameQueue1.add("John");
        nameQueue1.add("Blake");
        nameQueue1.add("Kevin");
        nameQueue1.add("Michael");
        nameQueue1.add("Walter");
        nameQueue1.add("Angel");

        nameQueue2.add("George");
        nameQueue2.add("Katie");
        nameQueue2.add("Kevin");
        nameQueue2.add("Michelle");
        nameQueue2.add("Ryan");
        nameQueue2.add("Angel");

        outputUnion(nameQueue1, nameQueue2);
        outputDifference(nameQueue1, nameQueue2);
        outputIntersection(nameQueue1, nameQueue2);
    }

    /* Function adds all the names from Queue1 to be outputted at the end.
       It then checks if a name in Queue2 is in Queue1.
       If it isn't, it adds it to the Queue to be outputted at the end. */
    public static void outputUnion(PriorityQueue<String> q1, PriorityQueue<String>q2) {

        PriorityQueue<String> union = new PriorityQueue();
        Iterator<String> iter1 = q1.iterator();
        Iterator<String> iter2 = q2.iterator();

        while(iter1.hasNext()) {
            union.add(iter1.next());
        }
        while(iter2.hasNext()) {
            String name = iter2.next();
            if(!q1.contains(name)) {
                union.add(name);
            }
        }

        Iterator<String> iter3 = union.iterator();
        System.out.println("Union: ");
        while(iter3.hasNext()) {
            System.out.println(iter3.next());
        }
        System.out.println();

    }

    /* Function uses the contain methods to check if a name is in the Queue2.
       If it isn't, the function adds it to a Queue to be outputted at the end.
       The Function then does the same for Queue2 by checking if a name in Queue2 is in Queue1. */
    public static void outputDifference(PriorityQueue<String> q1, PriorityQueue<String>q2) {
        PriorityQueue<String> difference = new PriorityQueue();
        Iterator<String> iter1 = q1.iterator();
        Iterator<String> iter2 = q2.iterator();

        while(iter1.hasNext()) {
            String name = iter1.next();
            if(!q2.contains(name)){
                difference.add(name);
            }
        }
        while(iter2.hasNext()) {
            String name = iter2.next();
            if(!q1.contains(name)) {
                difference.add(name);
            }
        }

        Iterator<String> iter3 = difference.iterator();
        System.out.println("Difference: ");
        while(iter3.hasNext()) {
            System.out.println(iter3.next());
        }
        System.out.println();

    }

    /* Function uses the contain methods to check if a name is in the other queue.
       If it is, the function adds it to a Queue to be outputted at the end. */
    public static void outputIntersection(PriorityQueue<String> q1, PriorityQueue<String>q2) {
        PriorityQueue<String> intersection = new PriorityQueue();
        Iterator<String> iter1 = q1.iterator();

        while(iter1.hasNext()) {
            String name = iter1.next();
            if(q2.contains(name)){
                intersection.add(name);
            }
        }

        Iterator<String> iter3 = intersection.iterator();
        System.out.println("Intersection: ");
        while(iter3.hasNext()) {
            System.out.println(iter3.next());
        }
        System.out.println();

    }
}
