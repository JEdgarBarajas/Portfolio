import java.util.*;

public class gradebook {

    public static Scanner scan = new Scanner(System.in);    //Int-Double Scanner
    public static Scanner read = new Scanner(System.in);    //String Scanner

    public static void main(String[] args) {
        int numStudents;
        int numCourses;
        int numAbove80;
        int numBelow70;
        String[] courseName;
        String[] courseSection;
        double[] courseAverage;
        double totalAward;

        //Inputting number of courses
        System.out.println("Welcome to the Gradebook.");
        System.out.println("Input the number of courses you teach: ");
        numCourses = scan.nextInt();

        courseName = new String[numCourses];
        courseAverage = new double[numCourses];
        courseSection = new String[numCourses];

        //First for loop. For loop for inputting course information
        for(int currCourse = 0; currCourse < numCourses; currCourse++) {

            System.out.println("Input course #" + (currCourse+1) + "'s name");
            courseName[currCourse] = read.nextLine();

            System.out.println("Input " +  courseName[currCourse] + " Section: ");
            courseSection[currCourse] = read.nextLine();

            System.out.println("Input the number of students in " + courseName[currCourse] + ": ");
            numStudents = scan.nextInt();

            courseAverage[currCourse] = 0;
            String[] studentName = new String[numStudents];
            int[] studentID = new int[numStudents];
            double[] studentAverage = new double[numStudents];
            int[] extraCredit = new int[numStudents];
            String[] studentGrade = new String[numStudents];

            //Start of Second For Loop. For Loop for inputting Student Information
            for(int currStudent = 0; currStudent < numStudents; currStudent++) {

                System.out.println("Input student #" + (currStudent + 1) + "'s name: ");
                studentName[currStudent] = read.nextLine();

                System.out.println("Input " + studentName[currStudent] + "'s ID: ");
                studentID[currStudent] = scan.nextInt();

                studentAverage[currStudent] = getGradeAverage(studentName[currStudent]);
                extraCredit[currStudent] = getExtraCredit(studentName[currStudent], studentAverage[currStudent]);
                studentGrade[currStudent] = getLetterGrade(studentAverage[currStudent], extraCredit[currStudent]);
                courseAverage[currCourse] += studentAverage[currStudent] + extraCredit[currStudent];

            }  //End of Student Input For Loop

            totalAward = 0;
            numAbove80 = 0;
            numBelow70 = 0;
            courseAverage[currCourse] = courseAverage[currCourse]/numStudents;

            //Start of Course Info Output
            System.out.println("Course: " + courseName[currCourse]);
            System.out.println("Section: " + courseSection[currCourse]);
            System.out.printf("Course Average: %.2f\n", courseAverage[currCourse]);
            System.out.println();

            //Start of third for loop. For loop for outputting Student Info.
            for(int currStudent = 0; currStudent < numStudents; currStudent++) {
                totalAward += scholarship(studentName[currStudent], studentID[currStudent], studentAverage[currStudent], extraCredit[currStudent], studentGrade[currStudent]);
                System.out.println();
                if (studentAverage[currStudent] + extraCredit[currStudent] > 80) {
                    numAbove80++;
                }
                if (studentAverage[currStudent] + extraCredit[currStudent] < 70) {
                    numBelow70++;
                }
            } //End of Student Output For Loop
            findLowAndHigh(studentAverage);
            System.out.println("Averages Above 80: " + numAbove80);
            System.out.println("Averages Below 70: " + numBelow70);
            System.out.printf("Total Scholarship Award for %s: $%.2f\n", courseName[currCourse], totalAward);
        } //End of Course Input For Loop

        //Start of 4th For Loop. For Loop for outputting Course Info.
        for(int currCourse = 0; currCourse < numCourses; currCourse++) {
            System.out.println();
            System.out.println("Course: " + courseName[currCourse]);
            System.out.printf("Course Average: %.2f\n", courseAverage[currCourse]);
        }//End of outputting course info for loop.
    }


    //Prompts user for grades, and returns the average of grades.
    public static double getGradeAverage(String student) {
        final double QUIZ_WEIGHT = 0.15;
        final double EXAM_WEIGHT = 0.20;
        final double PROGRAM_WEIGHT = 0.30;
        final double SLO_WEIGHT = 0.35;
        String assignName;
        double assignGrade;
        int assignIndex = 0;
        int assignType;
        double quizAverage = 0;
        int quizNum = 0;
        double examAverage = 0;
        int examNum = 0;
        double programAverage = 0;
        int programNum = 0;
        double sloAverage = 0;
        int sloNum = 0;
        double weightedAverage;

        System.out.println("Input assign #" + (assignIndex + 1) + "'s name OR input -1 to stop inputting " + student + "'s grades:");
        assignName = read.nextLine();

        //Start of While Loop. While Loop asks for assignment, type, and grade.
        while(!assignName.equals("-1")) {
            System.out.println("Input " + assignName + "'s type.");
            System.out.println("(1 = Quiz/Discussion; 2 = Exam; 3 = Program; 4 = SLO)");
            assignType = scan.nextInt();

            //Error checking assignment type While Loop.
            while(assignType > 4 || assignType < 1) {
                System.out.println("Invalid input. Please input a number from 1 to 4.");
                System.out.println("Input " + assignName + "'s type.");
                System.out.println("(1 = Quiz/Discussion; 2 = Exam; 3 = Program; 4 = SLO)");
                assignType = scan.nextInt();
            } //End of Type Error Checking While Loop.
            System.out.println("Input " + student + "'s grade on " + assignName);
            assignGrade = scan.nextDouble();

            //Error checking grade while loop
            while(assignGrade > 100 || assignGrade < 0) {
                System.out.println("Invalid Input. Assignment grades must be a number from 0 to 100.");
                System.out.println("Input " + student + "'s grade on " + assignName);
                assignGrade = scan.nextDouble();
            }//End of grade checking while loop

            switch(assignType) {
                case 1:
                    quizNum++;
                    quizAverage += assignGrade;
                    break;
                case 2:
                    examNum++;
                    examAverage += assignGrade;
                    break;
                case 3:
                    programNum++;
                    programAverage += assignGrade;
                    break;
                case 4:
                    sloNum++;
                    sloAverage += assignGrade;
                    break;
            }

            //Continue the Loop?
            assignIndex++;
            System.out.println("Input assign #" + (assignIndex + 1) + "'s name OR input -1 to stop inputting " + student + "'s grades:");
            assignName = read.nextLine();
        }

        quizAverage = quizAverage/quizNum;
        examAverage = examAverage/examNum;
        programAverage = programAverage/programNum;
        sloAverage = sloAverage/sloNum;
        weightedAverage = (quizAverage * QUIZ_WEIGHT) + (examAverage * EXAM_WEIGHT) + (programAverage * PROGRAM_WEIGHT) + (sloAverage * SLO_WEIGHT);

        return weightedAverage;

    }


    //Finds highest and lowest average in an array of grades
    public static void findLowAndHigh(double[] grades) {
        double highest = grades[0];
        double lowest = grades[0];
        for(int i = 0; i < grades.length; i++) {
            if(lowest > grades[i]) {
                lowest = grades[i];
            }
            if(highest < grades[i]) {
                highest = grades[i];
            }
        }
        System.out.printf("Highest grade average: %.2f\n", highest);
        System.out.printf("Lowest grade average: %.2f\n", lowest);
    }


    //Asks for extra credit
    public static int getExtraCredit(String student, double average) {
        int credit;
        System.out.println("Students can earn up to 5 points of Extra Credit.");
        System.out.println("Input the amount of Extra Credit " + student + " earned OR Input 0 to not give Extra Credit");
        credit = scan.nextInt();

        //Start of Error Checking While Loop for Extra Credit
        while(!(credit >= 0 && credit <= 5)) {
            System.out.println("Invalid input. Input a number from 0 to 5");
            System.out.println("Input the amount of Extra Credit " + student + " earned OR Input 0 to not give Extra Credit");
            credit = scan.nextInt();
        } //End of Extra Credit Checking While Loop
        return credit;
    }


    //Takes average and extra credit, Combines them, and returns a letter grade for the assignment.
    public static String getLetterGrade(double average, int credit) {
        String letter;
        int score = (int)((credit + average)/10);
        switch(score) {
            case 10:
                letter = "A";
                break;
            case 9:
                letter = "A";
                break;
            case 8:
                letter = "B";
                break;
            case 7:
                letter = "C";
                break;
            default:
                letter = "F";
                break;
        }
        return letter;
    }


    /*Takes information to output student information, then checks if student is eligible for
      a scholarship and awards aproppiate amount of money to student, and returns the amount */
    public static double scholarship(String student, int id, double average, int credit, String letter) {
        double award;
        if(average+credit > 95) {
            award = 750;
        }
        else if(average+credit > 90) {
            award = 500;
        }
        else if(average+credit > 85) {
            award = 250;
        }
        else {
            award = 0;
        }

        System.out.println("Student: " + student);
        System.out.println("Student ID: " + id);
        System.out.printf("Student Average before Extra Credit: %.2f\n", average);
        System.out.printf("Student Average w/ Extra Credit: %.2f\n", (credit + average));
        System.out.println("Student Grade: " + letter);
        System.out.printf("Scholarship award amount: $%.2f\n", award);

        return award;
    }
}

