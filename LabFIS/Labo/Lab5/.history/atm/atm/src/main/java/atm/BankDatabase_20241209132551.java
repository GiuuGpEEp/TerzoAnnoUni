package atm;

public class BankDatabase
{
   private Account[] accounts;

   public BankDatabase() {
      accounts = new Account[2]; // just 2 accounts for testing
      accounts[0] = new Account(12345, 54321, new Euro(1000.0), new Euro(1200.0));
      accounts[1] = new Account(98765, 56789, new Euro(200.0), new Euro(200.0));  
   } 

   private Account getAccount(int accountNumber) {
      for (Account currentAccount : accounts)
         if (currentAccount.getAccountNumber() == accountNumber)
            return currentAccount;
      return null;
   }

   public boolean authenticateUser(int userAccountNumber, int userPIN) {
      Account userAccount = getAccount(userAccountNumber);
      if (userAccount != null)
         return userAccount.validatePIN(userPIN);
      else
         return false;
   }

   public Euro getAvailableBalance(int userAccountNumber) {
      return getAccount(userAccountNumber).getAvailableBalance();
   }

   public Euro getTotalBalance(int userAccountNumber) {
      return getAccount(userAccountNumber).getTotalBalance();
   }

   public void credit(int userAccountNumber, Euro amount) {
      getAccount(userAccountNumber).credit(amount);
   }

   public void debit(int userAccountNumber, Euro amount) {
      getAccount(userAccountNumber).debit(amount);
   }
}