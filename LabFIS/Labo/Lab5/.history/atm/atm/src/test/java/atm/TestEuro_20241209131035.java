package atm;

import org.junit.jupiter.api.BeforeEach;
import org.junit.jupiter.api.Test;
import static org.junit.jupiter.api.Assertions.*;

class TestEuro {

    private Euro euro1;
    private Euro euro2;

    @BeforeEach
    public void setUp() {
        euro1 = new Euro(530.5);
        euro2 = new Euro(100);
    }

    @Test
    public void testConstructor() {
        assertNotNull(euro1);
        assertNotNull(euro2);
        assertEquals(530.5, euro1.getAmount());
        assertEquals(100, euro2.getAmount());
    }

    @Test
    public void testAddition() {
        Euro result = euro1.add(euro2);
        assertEquals(630.5, result.getAmount());
    }

    @Test
    public void testSubtraction() {
        Euro result = euro1.subtract(euro2);
        assertEquals(430.5, result.getAmount());
    }

    @Test
    public void testEquality() {
        Euro euro3 = new Euro(530.5);
        assertTrue(euro1.equals(euro3));
        assertFalse(euro1.equals(euro2));
    }
}
