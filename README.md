# NatterboxSpeedDials
### http query and supporting admin tools to enable a speed dial facility on Natterbox
project uses a sqlite database to hold a list of names and phone numbers with a speed dial code in the range 8000-8999

index.php is called from a Natterbox http query component using GET with a parameter snumber = \<speed dial code\>

Returns an XML record

```
<records>
  <record>
    <Number>+44123456789</Number>
    <Name>Fred Blogs</Name>
    <Error>OK</Error>
  </record>
<records>
```

If Error is not "OK" then it will contain a literal error string that can be used in a voice annoucement. Otherwise the Name field can be used to inform the caller who is being called and the Number Field used to dial.

The admin folder should be password protected and supplies a simple web interface to add, edit and delete records. If phone numbers are added in UK format, spaces are removed, the leading 0 removed and replaced with "+44"

