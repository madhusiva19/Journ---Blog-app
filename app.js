// app.js — Tiny helper for Markdown toolbar
function $(id){ return document.getElementById(id); }

// Wrap selected text
function wrap(before, after) {
  const ta = $('content'); if (!ta) return;
  const s = ta.selectionStart, e = ta.selectionEnd;
  const val = ta.value;
  const sel = val.slice(s, e);
  ta.value = val.slice(0, s) + before + sel + after + val.slice(e);
  const pos = s + before.length + sel.length;
  ta.focus();
  ta.setSelectionRange(pos, pos);
}

// Insert prefix at the start of the current line
function insertLinePrefix(prefix) {
  const ta = $('content'); if (!ta) return;
  const s = ta.selectionStart, e = ta.selectionEnd;
  const val = ta.value;
  const lineStart = val.lastIndexOf('\n', s - 1) + 1;
  ta.value = val.slice(0, lineStart) + prefix + val.slice(lineStart);
  const shift = prefix.length;
  ta.focus();
  ta.setSelectionRange(s + shift, e + shift);
}

// Toolbar buttons
function mdBold()   { wrap('**','**'); }
function mdItalic() { wrap('*','*'); }
function mdCode()   { wrap('`','`'); }
function mdH1()     { insertLinePrefix('# '); }
function mdUL()     { insertLinePrefix('- '); }
function mdQuote()  { insertLinePrefix('> '); }
