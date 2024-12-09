package atm;

public class Euro {
   private double value;

   public Euro(double value) {
      this.value = value;
   }

   public double getValue() {
      return value;
   }

   public Euro sum(Euro other) {
      return new Euro(this.value + other.value);
   }

   public Euro subtract(Euro other) {
      return new Euro(this.value - other.value);
   }
}