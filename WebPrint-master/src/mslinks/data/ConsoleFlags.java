package mslinks.data;

import io.ByteReader;

import java.io.IOException;

public class ConsoleFlags extends BitSet32 {

	public ConsoleFlags(int n) {
		super(n);
	}
	
	public ConsoleFlags(ByteReader data) throws IOException {
		super(data);
	}
	
	public boolean isBoldFont() { return get(0); }
	public boolean isFullscreen() { return get(1); }
	public boolean isQuickEdit() { return get(2); }
	public boolean isInsertMode() { return get(3); }
	public boolean isAutoPosition() { return get(4); }
	public boolean isHistoryDup() { return get(5); }
	
	public ConsoleFlags setBoldFont() { set(0); return this; }
	public ConsoleFlags setFullscreen() { set(1); return this; }
	public ConsoleFlags setQuickEdit() { set(2); return this; }
	public ConsoleFlags setInsertMode() { set(3); return this; }
	public ConsoleFlags setAutoPosition() { set(4); return this; }
	public ConsoleFlags setHistoryDup() { set(5); return this; }
	
	public ConsoleFlags clearBoldFont() { clear(0); return this; }
	public ConsoleFlags clearFullscreen() { clear(1); return this; }
	public ConsoleFlags clearQuickEdit() { clear(2); return this; }
	public ConsoleFlags clearInsertMode() { clear(3); return this; }
	public ConsoleFlags clearAutoPosition() { clear(4); return this; }
	public ConsoleFlags clearHistoryDup() { clear(5); return this; }

}
