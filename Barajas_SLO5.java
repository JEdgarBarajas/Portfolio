import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.awt.Color;
import java.util.*;
import javax.swing.*;
import java.lang.Double;

public class Barajas_SLO5 {

    public static void main(String[] args) {

        ListFrame numberList = new ListFrame();

    }

    public static class ListFrame {
        public static JPanel mainPanel;
        public static JFrame frame;
        public static InputPanel insertBox;
        public static ListDisplayPanel listBox;

        public ListFrame() {

            frame = new JFrame();
            frame.setSize(1000, 400);
            frame.setTitle("Number List");
            frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);

            mainPanel = new JPanel();
            insertBox = new InputPanel();
            listBox = new ListDisplayPanel(insertBox.numList);

            mainPanel.add(insertBox.panel);
            mainPanel.add(listBox.panel);

            frame.add(mainPanel);
            frame.setVisible(true);

        }

        public class ListRefresh implements ActionListener {

            public void actionPerformed(ActionEvent e) {

                //listBox.numList = insertBox.numList;
                //listBox.listArea.setText(insertBox.toString());


            }

        }

        public static class InputPanel {
            public static LinkedList<Double> numList = new LinkedList<Double>();
            public static JPanel panel = new JPanel();
            public static JTextField inputField = new JTextField(25);
            public static JButton submitButton = new JButton("Submit");

            public InputPanel(){
                submitButton.addActionListener(new AddNumber());

                this.panel.add(new JLabel("Input Number: "));
                this.panel.add(inputField);
                this.panel.add(submitButton);
            }

            public static class AddNumber implements ActionListener {
                public AddNumber() {
                }

                public void actionPerformed(ActionEvent e) {
                    JLabel warning;
                    double input = Double.parseDouble(inputField.getText());

                    if(!Double.isNaN(input) && numList.size() < 15) {
                        if(numList.contains(input)) {
                            warning = new JLabel("You Must Input a Unique Number");
                        }
                        else {
                            numList.add(input);
                            warning = new JLabel();
                            inputField.setText("");
                            mainPanel.remove(listBox.panel);
                            listBox = new ListDisplayPanel(insertBox.numList);
                            mainPanel.add(listBox.panel);
                            frame.setVisible(true);

                        }

                    }
                    else if(numList.size() >= 15) {
                        warning = new JLabel("There are already 15 Numbers");
                    }
                    else {
                        warning = new JLabel("Not a Valid Number");
                    }
                    warning.setForeground(Color.RED);
                    panel.add(warning);
                }
            }

            public String toString() {
                String content = "";
                ListIterator<Double> iter = numList.listIterator(0);
                if(numList.size() > 0) {
                    content += iter.next();
                    while(iter.hasNext()) {
                        content += ", " + iter.next();
                    }
                }
                return content;
            }


        }

        public static class ListDisplayPanel {

            public static JTextArea listArea;
            public static LinkedList<Double> numList;
            public static JPanel panel;
            public static JButton sortButton;
            public static JButton reverseButton;
            public static JButton shuffleButton;

            public ListDisplayPanel(LinkedList<Double> list) {
                panel = new JPanel();
                numList = list;
                ListIterator iter = numList.listIterator(0);

                String content = "";
                while(iter.hasNext()) {
                    content += iter.next() + ", ";
                }
                content += "\n";

                listArea = new JTextArea(content,5, 50);
                listArea.setEditable(false);

                sortButton = new JButton("Sort");
                reverseButton = new JButton("Reverse");
                shuffleButton = new JButton("Shuffle");

                sortButton.addActionListener(new SortList());
                reverseButton.addActionListener(new ReverseList());
                shuffleButton.addActionListener(new ShuffleList());

                JPanel fieldPanel = new JPanel();
                fieldPanel.add(listArea);

                JPanel buttonsPanel = new JPanel();
                buttonsPanel.add(sortButton);
                buttonsPanel.add(reverseButton);
                buttonsPanel.add(shuffleButton);

                panel.add(fieldPanel);
                panel.add(buttonsPanel);

            }

            public static class SortList implements ActionListener {

                public SortList() {}

                public void actionPerformed(ActionEvent e) {
                    int len = numList.size();
                    double storedVal;

                    if(len > 4) {
                        for (int i = 0; i < len; i++) {

                            int maxIndex = 0;
                            for(int j = 0; j < len - i; j++) {
                                if(numList.get(maxIndex) < numList.get(j)) {
                                    maxIndex = j;
                                }
                            }

                            storedVal = numList.get(len - i - 1);
                            numList.set(len - i - 1, numList.get(maxIndex));
                            numList.set(maxIndex, storedVal);

                        }

                        String content = "";
                        ListIterator<Double> iter = numList.listIterator(0);
                        while(iter.hasNext()) {
                            content += iter.next() + ", ";
                        }
                        content += "\n";
                        listArea.setText(content);

                    }
                }
            }


            public static class ShuffleList implements ActionListener {
                public ShuffleList() {}

                public void actionPerformed(ActionEvent e) {

                    int len = numList.size();
                    if(len > 4) {
                        int half = len/2;
                        if(len%2 == 0) {
                            half--;
                        }

                        double temp;
                        ListIterator<Double> iter;

                        //Will Guarantee a unique order, as long as each element in the list is unique
                        if(len > 1) {

                            if(numList.get(len - 1) < numList.get(0)) {

                                temp = numList.get(len - 1);
                                numList.set(len - 1, numList.get(0));
                                numList.set(0, temp);

                            }

                            if(numList.get(len - 1) > numList.get(0)) {

                                for(int i = 0; i < half; i++) {
                                    temp = numList.removeLast();
                                    numList.addFirst(temp);
                                }

                            }
                        }

                        String content = "";
                        iter = numList.listIterator(0);
                        while(iter.hasNext()) {
                            content += iter.next() + ", ";
                        }
                        content += "\n";
                        listArea.setText(content);

                    }
                }
            }


            public static class ReverseList implements ActionListener {

                public LinkedList<Double> originalList = new LinkedList<Double>();

                public ReverseList() {
                    ListIterator<Double> iter = numList.listIterator(0);
                    while(iter.hasNext()) {
                        this.originalList.add(iter.next());
                    }
                }

                public void actionPerformed(ActionEvent e) {

                    ListIterator<Double> iter = originalList.listIterator(originalList.size());

                    if(numList.size() > 4) {
                        String content = "";
                        while(iter.hasPrevious()) {
                            content += iter.previous() + ", ";
                        }
                        content += "\n";
                        listArea.setText(content);
                    }
                }

            }

        }

    }


}