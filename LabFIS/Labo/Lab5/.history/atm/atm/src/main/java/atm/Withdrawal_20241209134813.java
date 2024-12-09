package atm;

public class Withdrawal extends Transaction {

   private final Keypad keypad;
   private final CashDispenser cashDispenser;
   private static final int CANCELED = 0;
   private static final int INVALID = 6;

   public Withdrawal(int userAccountNumber, Screen atmScreen, BankDatabase atmBankDatabase, 
                     Keypad atmKeypad, CashDispenser atmCashDispenser) {
      super(userAccountNumber, atmScreen, atmBankDatabase);
      keypad = atmKeypad;
      cashDispenser = atmCashDispenser;
   }

   // perform transaction
   @Override
   public void execute() {
      boolean cashDispensed = false; // cash was not dispensed yet
      Euro availableBalance; // amount available for withdrawal
      // get references to bank database and screen
      BankDatabase bankDatabase = getBankDatabase(); 
      Screen screen = getScreen();
      // loop until cash is dispensed or the user cancels
      do {
         // obtain a chosen withdrawal amount from the user 
         Euro amount = new Euro(displayMenuOfAmounts());
         // check whether user chose a withdrawal amount or canceled
         if (amount.getValue() != CANCELED) {
            availableBalance = bankDatabase.getAvailableBalance(getAccountNumber());
            // check whether the user has enough money in the account 
            if (amount.getValue() <= availableBalance.getValue()) {   
               // check whether the cash dispenser has enough money
               if (cashDispenser.isSufficientCashAvailable(amount.getValue())) {
                  bankDatabase.debit(getAccountNumber(), amount);
                  cashDispenser.dispenseCash(amount.getValue()); // dispense cash
                  cashDispensed = true; // cash was dispensed
                  // instruct user to take cash
                  screen.displayMessageLine("\nPlease take your cash now.");
               } 
               else // cash dispenser does not have enough cash
                  screen.displayMessageLine("""
                     Insufficient cash available in the ATM.
                     Please choose a smaller amount.
                  """);
            } 
            else // not enough money available in user's account
               screen.displayMessageLine("""
                  Insufficient funds in your account.
                  Please choose a smaller amount.
               """);
         } 
         else { // user chose cancel menu option 
            screen.displayMessageLine("\nCanceling transaction...");
            break;
         }
      } while (!cashDispensed);
   }

   // display a menu of withdrawal amounts and the option to cancel
   // return the chosen amount or 0 if the user chooses to cancel
   private double displayMenuOfAmounts() {
      int userChoice = INVALID; // local variable to store return value
      Screen screen = getScreen();
      // array of amounts to correspond to menu numbers
      int[] amounts = {0, 20, 40, 60, 100, 200};
      // loop while no valid choice has been made
      while (userChoice == INVALID) {
         // display the menu
         screen.displayMessageLine("\nWithdrawal Menu:");
         screen.displayMessageLine("1 - 20 Euro");
         screen.displayMessageLine("2 - 40 Euro");
         screen.displayMessageLine("3 - 60 Euro");
         screen.displayMessageLine("4 - 100 Euro");
         screen.displayMessageLine("5 - 200 Euro");
         screen.displayMessageLine("0 - Cancel transaction");
         screen.displayMessage("\nChoose a withdrawal amount: ");
         int input = keypad.getInput(); // get user input through keypad
         switch (input) {
            case 1, 2, 3, 4, 5 -> userChoice = amounts[input]; // save user's choice
            case CANCELED -> userChoice = CANCELED; // save user's choice
            default -> screen.displayMessageLine("\nInvalid selection. Try again.");
         }
      }
      return userChoice; // return withdrawal amount or CANCELED
   }
}