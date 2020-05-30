<table width="100%">
    <tr>
        <td>
            
            <button onclick="absdate_close()" style="height:22px;width:15px;padding:0px;margin-right:10px;">»</button>
            <button class="absprev" onclick="changemonth('prev')"></button>
            <select id="absmonth" onclick="changemonth(this)">
                <option value="1">January</option>
                <option value="2">February</option>
                <option value="3">March</option>
                <option value="4">April</option>
                <option value="5">May</option>
                <option value="6">June</option>
                <option value="7">July</option>
                <option value="8">August</option>
                <option value="9">September</option>
                <option value="10">October</option>
                <option value="11">November</option>
                <option value="12">December</option>                
            </select>
            <button class="absnext" onclick="changemonth('next')"></button>
        </td>
        <td align="right">
            <button class="absprev" onclick="changeyear('prev')"></button>
            <input type="text" id="absyear" onblur="changeyear('null')"/>
            <button class="absnext" onclick="changeyear('next')"></button>

            <input type="hidden" id="absselectedyear"/>
            <input type="hidden" id="absselectedmonth"/>
            <input type="hidden" id="absdate"/>
            <input type="hidden" id="absid"/>
            <input type="hidden" id="abspath"/>
        </td>
    </tr>
</table>