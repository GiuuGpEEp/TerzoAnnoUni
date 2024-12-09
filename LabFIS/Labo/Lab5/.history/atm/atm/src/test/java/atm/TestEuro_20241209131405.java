package atm;

import static org.junit.jupiter.api.Assertions.assertEquals;
import static org.junit.jupiter.api.Assertions.fail;
import org.junit.jupiter.api.BeforeEach;
import org.junit.jupiter.api.Test;


class TestEuro {

    private Euro euro1;
    private Euro euro2;
    
    @BeforeEach
    public void setUp() {
        euro1 = new Euro(530.5);
        euro2 = new Euro(100);
    }

    @Test
    void testSum() {
        Euro result = euro1.sum(euro2);
        assertEquals(630.5, result.getValue()(), 0.001);
    }

    @Test
    void testSubtract() {
        Euro result = euro1.subtract(euro2);
        assertEquals(430.5, result.getAmount(), 0.001);
    }

    @Test
    void testMultiply() {
        Euro result = euro1.multiply(2);
        assertEquals(1061.0, result.getAmount(), 0.001);
    }

    @Test
    void testDivide() {
        Euro result = euro1.divide(2);
        assertEquals(265.25, result.getAmount(), 0.001);
    }
   
}
