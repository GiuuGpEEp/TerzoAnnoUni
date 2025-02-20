package atm;

public class Euro {

	private double value;

	public Euro(double v) {
		value = v;
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

	public boolean equalTo(Euro e){
		return (value == e.getValue());
	}
	
	public boolean lessThan(Euro e){
		return (value <= e.getValue());
	}

	public String print(){
		return value +" euro";
	}
}