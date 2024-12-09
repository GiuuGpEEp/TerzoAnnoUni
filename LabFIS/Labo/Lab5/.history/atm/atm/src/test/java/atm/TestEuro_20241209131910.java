package atm;

import static org.junit.jupiter.api.Assertions.assertEquals;
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
        assertEquals(630.5, result.getValue());
    }

    @Test
    void testSubtract() {
        Euro result = euro1.subtract(euro2);
        assertEquals(430.5, result.getValue());
    }
     
    @Te
   
}
