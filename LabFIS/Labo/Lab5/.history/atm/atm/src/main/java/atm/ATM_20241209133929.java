package atm;

public class ATM {

   private boolean userAuthenticated; // whether user is authenticated
   private int currentAccountNumber; // current user's account number
   private Screen screen; // ATM's screen
   private Keypad keypad; // ATM's keypad
   private CashDispenser cashDispenser; // ATM's cash dispenser
   private DepositSlot depositSlot; // ATM's deposit slot
   private BankDatabase bankDatabase; // account information database

   // constants corresponding to main menu options
   private static final int BALANCE_INQUIRY = 1;
   private static final int WITHDRAWAL = 2;
   private static final int DEPOSIT = 3;
   private static final int EXIT = 0;

   public ATM() {
      userAuthenticated = false; // user is not authenticated to start
      currentAccountNumber = 0; // no current account number to start
      screen = new Screen(); // create screen
      keypad = new Keypad(); // create keypad 
      cashDispenser = new CashDispenser(); // create cash dispenser
      depositSlot = new DepositSlot(); // create deposit slot
      bankDatabase = new BankDatabase(); // create acct info database
   }

   // start ATM 
   public void run() {
      // welcome and authenticate user; perform transactions
      while (true) {
         while (!userAuthenticated) {
            screen.displayMessageLine("\nWelcome!");
            authenticateUser();
         }
         performTransactions();
         userAuthenticated = false;
         currentAccountNumber = 0;
         screen.displayMessageLine("\nThank you! Goodbye!");
      }
   }

   private void authenticateUser() {
      screen.displayMessage("\nPlease enter your account number: ");
      int accountNumber = keypad.getInput();
      screen.displayMessage("\nEnter your PIN: ");
      int pin = keypad.getInput();
      userAuthenticated = bankDatabase.authenticateUser(accountNumber, pin);
      if (userAuthenticated) {
         currentAccountNumber = accountNumber;
      } else {
         screen.displayMessageLine("Invalid account number or PIN. Please try again.");
      }
   }

   private void performTransactions() {
      Transaction currentTransaction = null;
      boolean userExited = false;
      while (!userExited) {
         int mainMenuSelection = displayMainMenu();
         switch (mainMenuSelection) {
            case BALANCE_INQUIRY:
            case WITHDRAWAL:
            case DEPOSIT:
               currentTransaction = createTransaction(mainMenuSelection);
               currentTransaction.execute();
               break;
            case EXIT:
               screen.displayMessageLine("\nExiting the system...");
               userExited = true;
               break;
            default:
               screen.displayMessageLine("\nYou did not enter a valid selection. Try again.");
               break;
         }
      }
   }

   private int displayMainMenu() {
      screen.displayMessageLine("\nMain menu:");
      screen.displayMessageLine("1 - View my balance");
      screen.displayMessageLine("2 - Withdraw cash");
      screen.displayMessageLine("3 - Deposit funds");
      screen.displayMessageLine("0 - Exit\n");
      screen.displayMessage("Enter a choice: ");
      return keypad.getInput();
   }

   private Transaction createTransaction(int type) {
      Transaction temp = null;
      switch (type) {
         case BALANCE_INQUIRY:
            temp = new BalanceInquiry(currentAccountNumber, screen, bankDatabase);
            break;
         case WITHDRAWAL:
            temp = new Withdrawal(currentAccountNumber, screen, bankDatabase, keypad, cashDispenser);
            break;
         case DEPOSIT:
            temp = new Deposit(currentAccountNumber, screen, bankDatabase, keypad, depositSlot);
            break;
      }
      return temp;
   }
}