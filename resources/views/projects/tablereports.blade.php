<div class="px-4 py-4 sm:rounded-lg">
    <!-- Controles de DataTables: Buscar y Mostrar registros -->
    <div class="mb-4 flex items-center justify-between">
        <div class="dataTables_filter">
            <div class="relative flex h-full w-full">
                <div class="absolute z-10 flex">
                    <span
                        class="whitespace-no-wrap flex items-center rounded-lg border-0 border-none bg-transparent p-2 leading-normal lg:px-3">
                        <svg width="18" height="18" class="w-4 lg:w-auto" viewBox="0 0 18 18" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M8.11086 15.2217C12.0381 15.2217 15.2217 12.0381 15.2217 8.11086C15.2217 4.18364 12.0381 1 8.11086 1C4.18364 1 1 4.18364 1 8.11086C1 12.0381 4.18364 15.2217 8.11086 15.2217Z"
                                stroke="#455A64" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M16.9993 16.9993L13.1328 13.1328" stroke="#455A64" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </span>
                </div>
                <input type="search" id="tableSearch" placeholder="Buscar reporte" class="inputs"
                    style="padding-left: 3em;" aria-controls="mytable">
            </div>
        </div>

        <div class="dataTables_length">
            <label class="flex">Mostrar
                <select name="mytable_length" id="tableLength" aria-controls="mytable" class="inputs mx-2">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select> registros
            </label>
        </div>
    </div>

    <table id="mytable" class="whitespace-no-wrap table-hover table w-full">
        <thead class="headTable border-0">
            <tr>
                <th class="px-2 py-3 text-left">Reporte</th>
                <th class="px-2 py-3 text-left">Delegado</th>
                <th class="px-2 py-3 text-left">Estado</th>
                <th class="px-2 py-3 text-left">Fecha de entrega</th>
                <th class="px-2 py-3 text-left">Creado</th>
                <th class="px-2 py-3 text-left">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="px-2 py-1">Tiger Nixon</td>
                <td class="px-2 py-1">System Architect</td>
                <td class="px-2 py-1">Edinburgh</td>
                <td class="px-2 py-1">61</td>
                <td class="px-2 py-1">2011-04-25</td>
                <td class="px-2 py-1">$320,800</td>
            </tr>
            <tr>
                <td class="px-2 py-1">Garrett Winters</td>
                <td class="px-2 py-1">Accountant</td>
                <td class="px-2 py-1">Tokyo</td>
                <td class="px-2 py-1">63</td>
                <td class="px-2 py-1">2011-07-25</td>
                <td class="px-2 py-1">$170,750</td>
            </tr>
            <tr>
                <td class="px-2 py-1">Ashton Cox</td>
                <td class="px-2 py-1">Junior Technical Author</td>
                <td class="px-2 py-1">San Francisco</td>
                <td class="px-2 py-1">66</td>
                <td class="px-2 py-1">2009-01-12</td>
                <td class="px-2 py-1">$86,000</td>
            </tr>
            <tr>
                <td class="px-2 py-1">Cedric Kelly</td>
                <td class="px-2 py-1">Senior Javascript Developer</td>
                <td class="px-2 py-1">Edinburgh</td>
                <td class="px-2 py-1">22</td>
                <td class="px-2 py-1">2012-03-29</td>
                <td class="px-2 py-1">$433,060</td>
            </tr>
            <tr>
                <td class="px-2 py-1">Airi Satou</td>
                <td class="px-2 py-1">Accountant</td>
                <td class="px-2 py-1">Tokyo</td>
                <td class="px-2 py-1">33</td>
                <td class="px-2 py-1">2008-11-28</td>
                <td class="px-2 py-1">$162,700</td>
            </tr>
            <tr>
                <td class="px-2 py-1">Brielle Williamson</td>
                <td class="px-2 py-1">Integration Specialist</td>
                <td class="px-2 py-1">New York</td>
                <td class="px-2 py-1">61</td>
                <td class="px-2 py-1">2012-12-02</td>
                <td class="px-2 py-1">$372,000</td>
            </tr>
            <tr>
                <td class="px-2 py-1">Herrod Chandler</td>
                <td class="px-2 py-1">Sales Assistant</td>
                <td class="px-2 py-1">San Francisco</td>
                <td class="px-2 py-1">59</td>
                <td class="px-2 py-1">2012-08-06</td>
                <td class="px-2 py-1">$137,500</td>
            </tr>
            <tr>
                <td class="px-2 py-1">Rhona Davidson</td>
                <td class="px-2 py-1">Integration Specialist</td>
                <td class="px-2 py-1">Tokyo</td>
                <td class="px-2 py-1">55</td>
                <td class="px-2 py-1">2010-10-14</td>
                <td class="px-2 py-1">$327,900</td>
            </tr>
            <tr>
                <td class="px-2 py-1">Colleen Hurst</td>
                <td class="px-2 py-1">Javascript Developer</td>
                <td class="px-2 py-1">San Francisco</td>
                <td class="px-2 py-1">39</td>
                <td class="px-2 py-1">2009-09-15</td>
                <td class="px-2 py-1">$205,500</td>
            </tr>
            <tr>
                <td class="px-2 py-1">Sonya Frost</td>
                <td class="px-2 py-1">Software Engineer</td>
                <td class="px-2 py-1">Edinburgh</td>
                <td class="px-2 py-1">23</td>
                <td class="px-2 py-1">2008-12-13</td>
                <td class="px-2 py-1">$103,600</td>
            </tr>
            <tr>
                <td class="px-2 py-1">Jena Gaines</td>
                <td class="px-2 py-1">Office Manager</td>
                <td class="px-2 py-1">London</td>
                <td class="px-2 py-1">30</td>
                <td class="px-2 py-1">2008-12-19</td>
                <td class="px-2 py-1">$90,560</td>
            </tr>
            <tr>
                <td class="px-2 py-1">Quinn Flynn</td>
                <td class="px-2 py-1">Support Lead</td>
                <td class="px-2 py-1">Edinburgh</td>
                <td class="px-2 py-1">22</td>
                <td class="px-2 py-1">2013-03-03</td>
                <td class="px-2 py-1">$342,000</td>
            </tr>
            <tr>
                <td class="px-2 py-1">Charde Marshall</td>
                <td class="px-2 py-1">Regional Director</td>
                <td class="px-2 py-1">San Francisco</td>
                <td class="px-2 py-1">36</td>
                <td class="px-2 py-1">2008-10-16</td>
                <td class="px-2 py-1">$470,600</td>
            </tr>
            <tr>
                <td class="px-2 py-1">Haley Kennedy</td>
                <td class="px-2 py-1">Senior Marketing Designer</td>
                <td class="px-2 py-1">London</td>
                <td class="px-2 py-1">43</td>
                <td class="px-2 py-1">2012-12-18</td>
                <td class="px-2 py-1">$313,500</td>
            </tr>
            <tr>
                <td class="px-2 py-1">Tatyana Fitzpatrick</td>
                <td class="px-2 py-1">Regional Director</td>
                <td class="px-2 py-1">London</td>
                <td class="px-2 py-1">19</td>
                <td class="px-2 py-1">2010-03-17</td>
                <td class="px-2 py-1">$385,750</td>
            </tr>
            <tr>
                <td class="px-2 py-1">Michael Silva</td>
                <td class="px-2 py-1">Marketing Designer</td>
                <td class="px-2 py-1">London</td>
                <td class="px-2 py-1">66</td>
                <td class="px-2 py-1">2012-11-27</td>
                <td class="px-2 py-1">$198,500</td>
            </tr>
            <tr>
                <td class="px-2 py-1">Paul Byrd</td>
                <td class="px-2 py-1">Chief Financial Officer (CFO)</td>
                <td class="px-2 py-1">New York</td>
                <td class="px-2 py-1">64</td>
                <td class="px-2 py-1">2010-06-09</td>
                <td class="px-2 py-1">$725,000</td>
            </tr>
            <tr>
                <td class="px-2 py-1">Gloria Little</td>
                <td class="px-2 py-1">Systems Administrator</td>
                <td class="px-2 py-1">New York</td>
                <td class="px-2 py-1">59</td>
                <td class="px-2 py-1">2009-04-10</td>
                <td class="px-2 py-1">$237,500</td>
            </tr>
            <tr>
                <td class="px-2 py-1">Bradley Greer</td>
                <td class="px-2 py-1">Software Engineer</td>
                <td class="px-2 py-1">London</td>
                <td class="px-2 py-1">41</td>
                <td class="px-2 py-1">2012-10-13</td>
                <td class="px-2 py-1">$132,000</td>
            </tr>
            <tr>
                <td class="px-2 py-1">Dai Rios</td>
                <td class="px-2 py-1">Personnel Lead</td>
                <td class="px-2 py-1">Edinburgh</td>
                <td class="px-2 py-1">35</td>
                <td class="px-2 py-1">2012-09-26</td>
                <td class="px-2 py-1">$217,500</td>
            </tr>
            <tr>
                <td class="px-2 py-1">Jenette Caldwell</td>
                <td class="px-2 py-1">Development Lead</td>
                <td class="px-2 py-1">New York</td>
                <td class="px-2 py-1">30</td>
                <td class="px-2 py-1">2011-09-03</td>
                <td class="px-2 py-1">$345,000</td>
            </tr>
            <tr>
                <td class="px-2 py-1">Yuri Berry</td>
                <td class="px-2 py-1">Chief Marketing Officer (CMO)</td>
                <td class="px-2 py-1">New York</td>
                <td class="px-2 py-1">40</td>
                <td class="px-2 py-1">2009-06-25</td>
                <td class="px-2 py-1">$675,000</td>
            </tr>
            <tr>
                <td class="px-2 py-1">Caesar Vance</td>
                <td class="px-2 py-1">Pre-Sales Support</td>
                <td class="px-2 py-1">New York</td>
                <td class="px-2 py-1">21</td>
                <td class="px-2 py-1">2011-12-12</td>
                <td class="px-2 py-1">$106,450</td>
            </tr>
            <tr>
                <td class="px-2 py-1">Doris Wilder</td>
                <td class="px-2 py-1">Sales Assistant</td>
                <td class="px-2 py-1">Sydney</td>
                <td class="px-2 py-1">23</td>
                <td class="px-2 py-1">2010-09-20</td>
                <td class="px-2 py-1">$85,600</td>
            </tr>
            <tr>
                <td class="px-2 py-1">Angelica Ramos</td>
                <td class="px-2 py-1">Chief Executive Officer (CEO)</td>
                <td class="px-2 py-1">London</td>
                <td class="px-2 py-1">47</td>
                <td class="px-2 py-1">2009-10-09</td>
                <td class="px-2 py-1">$1,200,000</td>
            </tr>
            <tr>
                <td class="px-2 py-1">Gavin Joyce</td>
                <td class="px-2 py-1">Developer</td>
                <td class="px-2 py-1">Edinburgh</td>
                <td class="px-2 py-1">42</td>
                <td class="px-2 py-1">2010-12-22</td>
                <td class="px-2 py-1">$92,575</td>
            </tr>
            <tr>
                <td class="px-2 py-1">Jennifer Chang</td>
                <td class="px-2 py-1">Regional Director</td>
                <td class="px-2 py-1">Singapore</td>
                <td class="px-2 py-1">28</td>
                <td class="px-2 py-1">2010-11-14</td>
                <td class="px-2 py-1">$357,650</td>
            </tr>
            <tr>
                <td class="px-2 py-1">Brenden Wagner</td>
                <td class="px-2 py-1">Software Engineer</td>
                <td class="px-2 py-1">San Francisco</td>
                <td class="px-2 py-1">28</td>
                <td class="px-2 py-1">2011-06-07</td>
                <td class="px-2 py-1">$206,850</td>
            </tr>
            <tr>
                <td class="px-2 py-1">Fiona Green</td>
                <td class="px-2 py-1">Chief Operating Officer (COO)</td>
                <td class="px-2 py-1">San Francisco</td>
                <td class="px-2 py-1">48</td>
                <td class="px-2 py-1">2010-03-11</td>
                <td class="px-2 py-1">$850,000</td>
            </tr>
            <tr>
                <td class="px-2 py-1">Shou Itou</td>
                <td class="px-2 py-1">Regional Marketing</td>
                <td class="px-2 py-1">Tokyo</td>
                <td class="px-2 py-1">20</td>
                <td class="px-2 py-1">2011-08-14</td>
                <td class="px-2 py-1">$163,000</td>
            </tr>
            <tr>
                <td class="px-2 py-1">Michelle House</td>
                <td class="px-2 py-1">Integration Specialist</td>
                <td class="px-2 py-1">Sydney</td>
                <td class="px-2 py-1">37</td>
                <td class="px-2 py-1">2011-06-02</td>
                <td class="px-2 py-1">$95,400</td>
            </tr>
            <tr>
                <td class="px-2 py-1">Suki Burks</td>
                <td class="px-2 py-1">Developer</td>
                <td class="px-2 py-1">London</td>
                <td class="px-2 py-1">53</td>
                <td class="px-2 py-1">2009-10-22</td>
                <td class="px-2 py-1">$114,500</td>
            </tr>
            <tr>
                <td class="px-2 py-1">Prescott Bartlett</td>
                <td class="px-2 py-1">Technical Author</td>
                <td class="px-2 py-1">London</td>
                <td class="px-2 py-1">27</td>
                <td class="px-2 py-1">2011-05-07</td>
                <td class="px-2 py-1">$145,000</td>
            </tr>
            <tr>
                <td class="px-2 py-1">Gavin Cortez</td>
                <td class="px-2 py-1">Team Leader</td>
                <td class="px-2 py-1">San Francisco</td>
                <td class="px-2 py-1">22</td>
                <td class="px-2 py-1">2008-10-26</td>
                <td class="px-2 py-1">$235,500</td>
            </tr>
            <tr>
                <td class="px-2 py-1">Martena Mccray</td>
                <td class="px-2 py-1">Post-Sales support</td>
                <td class="px-2 py-1">Edinburgh</td>
                <td class="px-2 py-1">46</td>
                <td class="px-2 py-1">2011-03-09</td>
                <td class="px-2 py-1">$324,050</td>
            </tr>
            <tr>
                <td class="px-2 py-1">Unity Butler</td>
                <td class="px-2 py-1">Marketing Designer</td>
                <td class="px-2 py-1">San Francisco</td>
                <td class="px-2 py-1">47</td>
                <td class="px-2 py-1">2009-12-09</td>
                <td class="px-2 py-1">$85,675</td>
            </tr>
            <tr>
                <td class="px-2 py-1">Howard Hatfield</td>
                <td class="px-2 py-1">Office Manager</td>
                <td class="px-2 py-1">San Francisco</td>
                <td class="px-2 py-1">51</td>
                <td class="px-2 py-1">2008-12-16</td>
                <td class="px-2 py-1">$164,500</td>
            </tr>
            <tr>
                <td class="px-2 py-1">Hope Fuentes</td>
                <td class="px-2 py-1">Secretary</td>
                <td class="px-2 py-1">San Francisco</td>
                <td class="px-2 py-1">41</td>
                <td class="px-2 py-1">2010-02-12</td>
                <td class="px-2 py-1">$109,850</td>
            </tr>
            <tr>
                <td class="px-2 py-1">Vivian Harrell</td>
                <td class="px-2 py-1">Financial Controller</td>
                <td class="px-2 py-1">San Francisco</td>
                <td class="px-2 py-1">62</td>
                <td class="px-2 py-1">2009-02-14</td>
                <td class="px-2 py-1">$452,500</td>
            </tr>
            <tr>
                <td class="px-2 py-1">Timothy Mooney</td>
                <td class="px-2 py-1">Office Manager</td>
                <td class="px-2 py-1">London</td>
                <td class="px-2 py-1">37</td>
                <td class="px-2 py-1">2008-12-11</td>
                <td class="px-2 py-1">$136,200</td>
            </tr>
            <tr>
                <td class="px-2 py-1">Jackson Bradshaw</td>
                <td class="px-2 py-1">Director</td>
                <td class="px-2 py-1">New York</td>
                <td class="px-2 py-1">65</td>
                <td class="px-2 py-1">2008-09-26</td>
                <td class="px-2 py-1">$645,750</td>
            </tr>
            <tr>
                <td class="px-2 py-1">Olivia Liang</td>
                <td class="px-2 py-1">Support Engineer</td>
                <td class="px-2 py-1">Singapore</td>
                <td class="px-2 py-1">64</td>
                <td class="px-2 py-1">2011-02-03</td>
                <td class="px-2 py-1">$234,500</td>
            </tr>
            <tr>
                <td class="px-2 py-1">Bruno Nash</td>
                <td class="px-2 py-1">Software Engineer</td>
                <td class="px-2 py-1">London</td>
                <td class="px-2 py-1">38</td>
                <td class="px-2 py-1">2011-05-03</td>
                <td class="px-2 py-1">$163,500</td>
            </tr>
            <tr>
                <td class="px-2 py-1">Sakura Yamamoto</td>
                <td class="px-2 py-1">Support Engineer</td>
                <td class="px-2 py-1">Tokyo</td>
                <td class="px-2 py-1">37</td>
                <td class="px-2 py-1">2009-08-19</td>
                <td class="px-2 py-1">$139,575</td>
            </tr>
            <tr>
                <td class="px-2 py-1">Thor Walton</td>
                <td class="px-2 py-1">Developer</td>
                <td class="px-2 py-1">New York</td>
                <td class="px-2 py-1">61</td>
                <td class="px-2 py-1">2013-08-11</td>
                <td class="px-2 py-1">$98,540</td>
            </tr>
            <tr>
                <td class="px-2 py-1">Finn Camacho</td>
                <td class="px-2 py-1">Support Engineer</td>
                <td class="px-2 py-1">San Francisco</td>
                <td class="px-2 py-1">47</td>
                <td class="px-2 py-1">2009-07-07</td>
                <td class="px-2 py-1">$87,500</td>
            </tr>
            <tr>
                <td class="px-2 py-1">Serge Baldwin</td>
                <td class="px-2 py-1">Data Coordinator</td>
                <td class="px-2 py-1">Singapore</td>
                <td class="px-2 py-1">64</td>
                <td class="px-2 py-1">2012-04-09</td>
                <td class="px-2 py-1">$138,575</td>
            </tr>
            <tr>
                <td class="px-2 py-1">Zenaida Frank</td>
                <td class="px-2 py-1">Software Engineer</td>
                <td class="px-2 py-1">New York</td>
                <td class="px-2 py-1">63</td>
                <td class="px-2 py-1">2010-01-04</td>
                <td class="px-2 py-1">$125,250</td>
            </tr>
            <tr>
                <td class="px-2 py-1">Zorita Serrano</td>
                <td class="px-2 py-1">Software Engineer</td>
                <td class="px-2 py-1">San Francisco</td>
                <td class="px-2 py-1">56</td>
                <td class="px-2 py-1">2012-06-01</td>
                <td class="px-2 py-1">$115,000</td>
            </tr>
            <tr>
                <td class="px-2 py-1">Jennifer Acosta</td>
                <td class="px-2 py-1">Junior Javascript Developer</td>
                <td class="px-2 py-1">Edinburgh</td>
                <td class="px-2 py-1">43</td>
                <td class="px-2 py-1">2013-02-01</td>
                <td class="px-2 py-1">$75,650</td>
            </tr>
            <tr>
                <td class="px-2 py-1">Cara Stevens</td>
                <td class="px-2 py-1">Sales Assistant</td>
                <td class="px-2 py-1">New York</td>
                <td class="px-2 py-1">46</td>
                <td class="px-2 py-1">2011-12-06</td>
                <td class="px-2 py-1">$145,600</td>
            </tr>
            <tr>
                <td class="px-2 py-1">Hermione Butler</td>
                <td class="px-2 py-1">Regional Director</td>
                <td class="px-2 py-1">London</td>
                <td class="px-2 py-1">47</td>
                <td class="px-2 py-1">2011-03-21</td>
                <td class="px-2 py-1">$356,250</td>
            </tr>
            <tr>
                <td class="px-2 py-1">Lael Greer</td>
                <td class="px-2 py-1">Systems Administrator</td>
                <td class="px-2 py-1">London</td>
                <td class="px-2 py-1">21</td>
                <td class="px-2 py-1">2009-02-27</td>
                <td class="px-2 py-1">$103,500</td>
            </tr>
            <tr>
                <td class="px-2 py-1">Jonas Alexander</td>
                <td class="px-2 py-1">Developer</td>
                <td class="px-2 py-1">San Francisco</td>
                <td class="px-2 py-1">30</td>
                <td class="px-2 py-1">2010-07-14</td>
                <td class="px-2 py-1">$86,500</td>
            </tr>
            <tr>
                <td class="px-2 py-1">Shad Decker</td>
                <td class="px-2 py-1">Regional Director</td>
                <td class="px-2 py-1">Edinburgh</td>
                <td class="px-2 py-1">51</td>
                <td class="px-2 py-1">2008-11-13</td>
                <td class="px-2 py-1">$183,000</td>
            </tr>
            <tr>
                <td class="px-2 py-1">Michael Bruce</td>
                <td class="px-2 py-1">Javascript Developer</td>
                <td class="px-2 py-1">Singapore</td>
                <td class="px-2 py-1">29</td>
                <td class="px-2 py-1">2011-06-27</td>
                <td class="px-2 py-1">$183,000</td>
            </tr>
            <tr>
                <td class="px-2 py-1">Donna Snider</td>
                <td class="px-2 py-1">Customer Support</td>
                <td class="px-2 py-1">New York</td>
                <td class="px-2 py-1">27</td>
                <td class="px-2 py-1">2011-01-25</td>
                <td class="px-2 py-1">$112,000</td>
            </tr>
        </tbody>
        <tfoot class="headTable border-0">
            <tr>
                <th class="px-2 py-3 text-left">Reporte</th>
                <th class="px-2 py-3 text-left">Delegado</th>
                <th class="px-2 py-3 text-left">Estado</th>
                <th class="px-2 py-3 text-left">Fecha de entrega</th>
                <th class="px-2 py-3 text-left">Creado</th>
                <th class="px-2 py-3 text-left">Acciones</th>
            </tr>
        </tfoot>
    </table>
    <div class="my-4 flex w-1/3 justify-start align-middle" id="paginationControls">
        <button id="prevPage" class="btnDisabled">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-caret-left">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path
                    d="M13.883 5.007l.058 -.005h.118l.058 .005l.06 .009l.052 .01l.108 .032l.067 .027l.132 .07l.09 .065l.081 .073l.083 .094l.054 .077l.054 .096l.017 .036l.027 .067l.032 .108l.01 .053l.01 .06l.004 .057l.002 .059v12c0 .852 -.986 1.297 -1.623 .783l-.084 -.076l-6 -6a1 1 0 0 1 -.083 -1.32l.083 -.094l6 -6l.094 -.083l.077 -.054l.096 -.054l.036 -.017l.067 -.027l.108 -.032l.053 -.01l.06 -.01z" />
            </svg>
        </button>
        <span id="pageInfo" class="mx-2 w-full">Página 1 de 1</span>
        <button id="nextPage" class="btnNuevo">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-caret-right">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path
                    d="M9 6c0 -.852 .986 -1.297 1.623 -.783l.084 .076l6 6a1 1 0 0 1 .083 1.32l-.083 .094l-6 6l-.094 .083l-.077 .054l-.096 .054l-.036 .017l-.067 .027l-.108 .032l-.053 .01l-.06 .01l-.057 .004l-.059 .002l-.059 -.002l-.058 -.005l-.06 -.009l-.052 -.01l-.108 -.032l-.067 -.027l-.132 -.07l-.09 -.065l-.081 -.073l-.083 -.094l-.054 -.077l-.054 -.096l-.017 -.036l-.027 -.067l-.032 -.108l-.01 -.053l-.01 -.06l-.004 -.057l-.002 -12.059z" />
            </svg>
        </button>
    </div>
</div>
