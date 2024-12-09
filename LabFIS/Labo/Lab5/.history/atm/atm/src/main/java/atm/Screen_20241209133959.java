package atm;

public class Screen {

   public void displayMessage(String message) {
      System.out.print(message);
   }

   public void displayMessageLine(String message) {
      System.out.println(message);
   }

   public void displayEuroAmount(double amount) {
      System.out.printf("€%,.2f", amount);
   }
}