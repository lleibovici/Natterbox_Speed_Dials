# Natterbox Speed Dials
### http query and supporting admin tools to enable a speed dial facility on Natterbox
Project uses a sqlite database to hold a list of names and phone numbers with a speed dial code in the range 8000-8999

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

### Natterbox Policy to use web service
![Natterbox Policy](/docs/policy.png "Natterbox Policy")

An "extention" component uses a regular expression to get any calls 8000 to 89999
![Extention Component](/docs/number.png "Extention Component")

The "HTTP Query" component calls the service and gets the result
![HTTP Query Component](/docs/httpquery.png "HTTP Query Component")

The "Compare" component checks for a succesful lookup
![Compare Component](/docs/compare.png "Compare Component")

If a successful result is returned the "Connect" Component calls the external number
![Connect Component](/docs/connect.png "Connect Component")

