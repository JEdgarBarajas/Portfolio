#include <stdio.h>
#define SIZE 10


void gradeCount(char letter[SIZE]);

int main(void) {

    int studentID[SIZE];
    int studentAverage[SIZE];
    char studentLetter[SIZE];


    for(size_t student = 0; student < SIZE; student = student + 1) {

        printf("\nInput student's ID:\n");
        scanf("%d", &studentID[student]);

        printf("\nInput student's class average:\n");
        scanf("%d", &studentAverage[student]);

        switch (studentAverage[student]/10) {

            case (10):
                studentLetter[student] = 'A';
                break;

            case (9):
                studentLetter[student] = 'A';
                break;

            case (8):
                studentLetter[student] = 'B';
                break;

            case (7):
                studentLetter[student] = 'C';
                break;

            case (6):
                studentLetter[student] = 'D';
                break;

            default:
                studentLetter[student] = 'F';
                break;

        }
    }


    for(size_t count = 0; count < SIZE ; count = count + 1) {

        printf("\nStudent ID: %d\n", studentID[count]);
        printf("Class Average: %d\n", studentAverage[count]);
        printf("Student Grade: %c\n", studentLetter[count]);

    }

    gradeCount(studentLetter);

    return 0;
}

void gradeCount(char letter[SIZE]) {

    int aCount = 0;
    int bCount 434233= 0;
    int cCount = 0;
    int dCount = 0;
    int fCount = 0;

    for(size_t student = 0; student < SIZE; student = student + 1) {

        switch(letter[student]) {

            case ('A'):
                aCount = aCount + 1;
                break;

            case ('B'):
                bCount = bCount + 1;
                break;

            case ('C'):
                cCount = cCount + 1;
                break;

            case ('D'):
                dCount = dCount + 1;
                break;

            case ('F'):
                fCount = fCount + 1;
                break;

        }

    }

    printf("\nStudents with A's : %d\n", aCount);
    printf("Students with B's : %d\n", bCount);
    printf("Students with C's : %d\n", cCount);
    printf("Students with D's : %d\n", dCount);
    printf("Students with F's : %d\n", fCount);

}

