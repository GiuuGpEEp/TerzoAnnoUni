package atm;

public class BalanceInquiry extends Transaction {
   public BalanceInquiry(int userAccountNumber, Screen atmScreen, BankDatabase atmBankDatabase) {
      super(userAccountNumber, atmScreen, atmBankDatabase);
   }

   public void execute() {
      BankDatabase bankDatabase = getBankDatabase();
      Screen screen = getScreen();
      Euro availableBalance = bankDatabase.getAvailableBalance(getAccountNumber());
      Euro totalBalance = bankDatabase.getTotalBalance(getAccountNumber());

      screen.displayMessageLine("\nBalance Information:");
      screen.displayMessage(" - Available balance: "); 
      screen.displayEuroAmount(availableBalance.getValue());
      screen.displayMessage("\n - Total balance:     ");
      screen.displayEuroAmount(totalBalance.getValue());
      screen.displayMessageLine("");
   } 
}