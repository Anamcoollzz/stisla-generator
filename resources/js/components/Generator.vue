<template>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">Generator</div>

            <div class="card-body">
                <form>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Path Projek</label>
                                <input type="text" class="form-control" v-model="data.path">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Nama Modul</label>
                                <input type="text" class="form-control" v-model="data.modul">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Ikon</label>
                                <input type="text" class="form-control" v-model="data.ikon">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Nama Controller</label>
                                <input type="text" class="form-control" v-model="data.controller">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Namespace Controller</label>
                                <input type="text" class="form-control" v-model="data.namespace_controller">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Nama Model</label>
                                <input type="text" class="form-control" v-model="data.model">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Namespace Model</label>
                                <input type="text" class="form-control" v-model="data.namespace_model">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Nama Tabel</label>
                                <input type="text" class="form-control" v-model="data.tabel">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Nama Migration</label>
                                <input type="text" class="form-control" v-model="data.migration">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Nama Seeder</label>
                                <input type="text" class="form-control" v-model="data.seeder">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Jumlah Data Dummy Seeder</label>
                                <input type="text" class="form-control" v-model="data.jumlah_seeder">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Folder Views</label>
                                <input type="text" class="form-control" v-model="data.views">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Namespace Views</label>
                                <input type="text" class="form-control" v-model="data.namespace_views">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card mt-4">
            <div class="card-header">Default Grup</div>
            <div class="card-body">
                <template v-for="(kol, i) in data.kolom">
                    <div :class="'row '+(i % 2 ? 'bg-biru' : 'bg-orange')">
                        <template v-if="kol.sembunyikan">
                            <div class="col-md-12">
                                <button class="btn btn-primary btn-sm" @click.prevent="perlihatkan(data.kolom[i])">Perlihatkan</button>
                            </div>
                        </template>
                        <template v-else>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="">Kolom {{ i+1 }} [view]</label>
                                    <input @keyup="setNama(data.kolom[i])" type="text" class="form-control" v-model="data.kolom[i].nama_asli">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="">Kolom {{ i+1 }} [basisdata]</label>
                                    <input @keyup="resetUniqueExists(data.kolom[i])" type="text" class="form-control" v-model="data.kolom[i].nama">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="">Tipe {{ i+1 }}</label>
                                    <select @change="onChangeTipe(data.kolom[i])" v-model="data.kolom[i].tipe" class="form-control">
                                        <option v-for="t in tipe" :value="t.value">{{ t.text }}</option>
                                    </select>
                                </div>
                            </div>
                            <template v-if="bukanPrimary(data.kolom[i].tipe)">
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label for="">Panjang {{ i+1 }}</label>
                                        <input type="number" min="0" class="form-control" v-model="data.kolom[i].panjang">
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <input @change="isUnique(data.kolom[i])" class="mt-5" type="checkbox" v-model="data.kolom[i].unique"> Unique
                                    <input @change="isNullable(data.kolom[i])" class="mt-5" type="checkbox" v-model="data.kolom[i].nullable"> Nullable
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label for="">Faker {{ i+1 }}</label>
                                        <select v-model="data.kolom[i].faker" class="form-control">
                                            <option value="name">name</option>
                                            <option value="nik">nik</option>
                                            <option value="email">email</option>
                                            <option value="city">city</option>
                                            <option value="phoneNumber">phoneNumber</option>
                                            <option value="digit">digit</option>
                                            <option value="jenisKelamin">jenisKelamin</option>
                                            <option value="tanggalSekarang">tanggalSekarang</option>
                                            <option value="address">address</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label for="">Ikon {{ i+1 }}</label>
                                        <input type="text" class="form-control" v-model="data.kolom[i].ikon">
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label for="">Lebar Form {{ i+1 }}</label>
                                        <select class="form-control" v-model="data.kolom[i].kolom_bootstrap">
                                            <option v-for="i in 12 " :value="'col-md-'+i">{{ 'col-md-'+i }}</option>
                                            <option v-for="i in 12 " :value="'col-sm-'+i">{{ 'col-sm-'+i }}</option>
                                            <option v-for="i in 12 " :value="'col-lg-'+i">{{ 'col-lg-'+i }}</option>
                                            <option v-for="i in 12 " :value="'col-xs-'+i">{{ 'col-xs-'+i }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label for="">Jenis Form {{ i+1 }}</label>
                                        <select class="form-control" v-model="data.kolom[i].jenis_form">
                                            <option value="input">input [text]</option>
                                            <option value="inputnumber">input [number]</option>
                                            <option value="inputimage">input [image]</option>
                                            <option value="select">select</option>
                                            <option value="datepicker">datepicker</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12" align="center">
                                    <h4 align="center">Validasi kolom {{ i+1 }}</h4>
                                    <input @change="hapusNullable(data.kolom[i])" type="checkbox" v-model="data.kolom[i].rules" value="required"> required
                                    <input @change="hapusNullable(data.kolom[i])" type="checkbox" v-model="grup.kolom[i].rules" value="nullable"> nullable
                                    <input type="checkbox" v-model="data.kolom[i].rules" value="email"> email
                                    <input type="checkbox" v-model="data.kolom[i].rules" value="string"> string
                                    <input type="checkbox" v-model="data.kolom[i].rules" value="numeric"> numeric
                                    <input type="checkbox" v-model="data.kolom[i].rules" value="file"> file
                                    <input type="checkbox" v-model="data.kolom[i].rules" value="mimes:jpeg,png"> image
                                    <input type="checkbox" v-model="data.kolom[i].rules" value="date_format:Y-m-d"> date_format:Y-m-d
                                    <br>

                                    <input type="checkbox" v-model="data.kolom[i].rules" :value="'unique:'+data.kolom[i].tabel_kolom"> unique
                                    <input type="text" v-model="data.kolom[i].tabel_kolom" @keyup="perbaruiUnik(data.kolom[i])" @change="perbaruiUnik(data.kolom[i])">

                                    <input type="checkbox" v-model="data.kolom[i].rules" :value="'exists:'+data.kolom[i].tabel_kolom"> exists
                                    <input type="text" v-model="data.kolom[i].tabel_kolom" @keyup="perbaruiUnik(data.kolom[i], false)" @change="perbaruiUnik(data.kolom[i], false)">

                                    <input type="checkbox" v-model="data.kolom[i].rules" :value="'min:'+data.kolom[i].min"> min
                                    <input @keydown="hapusmin(data.kolom[i])" type="number" min="0" v-model="data.kolom[i].min">

                                    <input type="checkbox" v-model="data.kolom[i].rules" :value="'max:'+data.kolom[i].max"> max
                                    <input @keydown="hapusmax(data.kolom[i])" type="number" min="0" v-model="data.kolom[i].max">

                                </div>
                                <div class="col-md-12" align="center">
                                    <br>
                                    <button @click.prevent="data.kolom.splice(i,1)" class="btn btn-danger btn-sm">Hapus</button>
                                    <button @click.prevent="sisipkanKolom(i, data.kolom)" class="btn btn-success btn-sm">Sisipkan Kolom</button>
                                    <button @click.prevent="sembunyikan(data.kolom[i])" class="btn btn-primary btn-sm">Sembunyikan</button>
                                    <hr>
                                </div>
                            </template>
                        </template>
                    </div>
                </template>
                <button @click.prevent="tambahKolom(data.kolom)" class="btn btn-success btn-block mb-4 mt-4">Tambah Kolom</button>
            </div>
        </div>
        <template v-for="(grup, indexGrup) in data.grup">
            <div class="card mt-4">
                <div class="card-header">{{ grup.nama }}
                    <div class="btn-group float-right">
                        <button class="btn btn-sm btn-success" @click.prevent="sisipkanGrup(indexGrup)">Sisipkan Grup</button>
                        <button class="btn btn-sm btn-warning" v-if="!grup.sembunyikan" @click.prevent="grup.sembunyikan = true">Sembunyikan</button>
                        <button class="btn btn-sm btn-primary" v-else @click.prevent="grup.sembunyikan = false">Perlihatkan</button>
                        <button class="btn btn-sm btn-danger" @click.prevent="hapusGrup(indexGrup)">Hapus</button>
                    </div>
                </div>
                <div class="card-body" v-if="!grup.sembunyikan">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Nama Grup</label>
                                <input type="text" class="form-control" v-model="grup.nama">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Lebar Card View</label>
                                <select class="form-control" v-model="grup.kolom_bootstrap">
                                    <option v-for="i in 12 " :value="'col-md-'+i">{{ 'col-md-'+i }}</option>
                                    <option v-for="i in 12 " :value="'col-sm-'+i">{{ 'col-sm-'+i }}</option>
                                    <option v-for="i in 12 " :value="'col-lg-'+i">{{ 'col-lg-'+i }}</option>
                                    <option v-for="i in 12 " :value="'col-xs-'+i">{{ 'col-xs-'+i }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <template v-for="(kol, i) in grup.kolom">
                        <kolom :tabel="tabel" :kolom="kol" :koloms="grup.kolom" :indeks="i" :modul-lainnya="modulLainnya" 
                        @sembunyikan="sembunyikan" 
                        @salin="salin" 
                        @sisipkanKolom="sisipkanKolom"></kolom>
                    </template>
                    <button @click.prevent="tambahKolom(grup.kolom)" class="btn btn-success btn-block mb-4 mt-4">Tambah Kolom</button>
                </div>
            </div>
            <button class="btn btn-success mt-2" @click.prevent="sisipkanGrup(indexGrup)">Sisipkan Grup</button>
        </template>
        <button class="btn btn-danger btn-block mt-4" @click.prevent="tambahGrup">Tambah Grup</button>
        <div style="height: 300px;"></div>
        <div class="card mt-4" style="position: fixed; bottom: 0; width: 95%;">
            <div class="card-header">
                Kuy Takis
            </div>
            <div class="card-body">
                <center>
                    <input type="checkbox" v-model="data.crud"> CRUD
                    <input type="checkbox" v-model="data.timestamps"> Timestamps
                </center>
                <button @click.prevent="generate" class="btn btn-primary btn-block">Generate</button>
                <div class="row" v-if="data.crud">
                    <div class="col-md-12">
                        <h4 align="center" class="mt-3">Copas ke web.php anda</h4>
                        <textarea class="form-control">Route::resource('{{ data.tabel }}', '{{ data.controller }}')->except('show');</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    // import KolomBootstrapSelect from './KolomBootstrapSelect.vue'
    import kolom from './Kolom.vue'
    import TIPE from './Tipe.vue'
    export default {
        data(){
            return {
                data : {
                    path : 'C:\\xampp\\htdocs\\ujicoba',
                    modul : '',
                    controller : '',
                    model : '',
                    migration : '',
                    seeder : '',
                    views : '',
                    kolom : [{
                        nama : 'id',
                        nama_asli : '',
                        tipe : 'bigIncrements',
                        panjang : null,
                        unique : false,
                        rules : [],
                        ikon : '',
                        tabel_kolom : 'tabel,kolom',
                        kolom_bootstrap : 'col-md-12',
                        sembunyikan : false,
                        min : '',
                        max : '',
                        nullable : false,
                    }],
                    timestamps : true,
                    tabel : '',
                    namespace_controller : '',
                    namespace_model : '',
                    namespace_views : '',
                    crud : true,
                    jumlah_seeder : 100,
                    ikon : '',
                    grup : [],
                },
                min : 0,
                max : 0,
                unique : 'tabel,kolom',
                exists : 'tabel,kolom',
                tipe : TIPE,
                grup_template : {
                    nama : 'Grup Baru',
                    sembunyikan : false,
                    kolom_bootstrap : 'col-md-6',
                    kolom : [{
                        nama : 'id',
                        nama_asli : '',
                        tipe : 'bigIncrements',
                        panjang : null,
                        unique : false,
                        rules : [],
                        ikon : '',
                        tabel_kolom : 'tabel,kolom',
                        kolom_bootstrap : 'col-md-12',
                        sembunyikan : false,
                    }],
                }
            }
        },
        mounted() {
            this.data.modul = this.modul.nama
            this.data.ikon = this.modul.ikon
            this.data.tabel = this.camelToSnake(this.data.modul)
            this.unique = this.data.tabel+',kolom'
            this.exists = this.data.tabel+',kolom'
            this.data.path = this.modul.projek.path
            this.data.grup = this.modul.grup
            console.log(this.data.grup)
            if(this.data.grup == undefined)
                this.data.grup = []
            // $('#rules').select2({
            //     multiple : true,
            // })
            // $('#rules').on('change.select', (e)=>{
            //     console.log(e)
            // })
            setTimeout(()=>{
                if(this.modul.controller)
                    this.data.controller = this.modul.controller
                if(this.modul.model)
                    this.data.model = this.modul.model
                if(this.modul.tabel)
                    this.data.tabel = this.modul.tabel
                if(this.modul.migration)
                    this.data.migration = this.modul.migration
                if(this.modul.seeder)
                    this.data.seeder = this.modul.seeder
                if(this.modul.views)
                    this.data.views = this.modul.views
                if(this.modul.jumlah_seeder)
                    this.data.jumlah_seeder = this.modul.jumlah_seeder
                if(this.modul.namespace_controller)
                    this.data.namespace_controller = this.modul.namespace_controller
                if(this.modul.namespace_model)
                    this.data.namespace_model = this.modul.namespace_model
                if(this.modul.namespace_views)
                    this.data.namespace_views = this.modul.namespace_views
                if(this.modul.kolom.length > 0)
                    this.data.kolom = this.modul.kolom
            }, 1000)

            // testing
            // this.tambahGrup()
        },
        watch : {
            'data.modul'(newValue){
                if(newValue.trim() == ''){
                    this.data.controller = ''
                    this.data.model = ''
                    this.data.tabel = ''
                    this.data.migration = ''
                    this.data.seeder = ''
                    this.data.views = ''
                    this.data.jumlah_seeder = 100
                    this.data.namespace_controller = ''
                    this.data.namespace_model = ''
                    this.data.namespace_views = ''
                }else{
                    let date = new Date()
                    let tahun = date.getFullYear()
                    let bulan = date.getMonth() + 1
                    let tanggal = date.getDate()
                    let jam = date.getHours()
                    let menit = date.getMinutes()
                    let detik = date.getSeconds()
                    newValue = this.titleCase(newValue)
                    let modulTanpaSpasi = newValue.replace(/ /g,'')
                    this.data.controller = modulTanpaSpasi+'Controller'
                    this.data.model = modulTanpaSpasi
                    this.data.migration = tahun+'_'+bulan+'_'+tanggal+'_'+jam+menit+detik+'_buat_tabel_'+this.camelToSnake(modulTanpaSpasi)
                    this.data.seeder = modulTanpaSpasi+'Seeder'
                    this.data.views = this.camelToSnake(modulTanpaSpasi)
                    this.data.tabel = this.data.views
                }
            },
            'data.tabel'(newValue){
                this.unique = newValue+',kolom'
                this.exists = newValue+',kolom'
            }
        },
        methods : {
            camelToSnake(string) {
                return string.replace(/[\w]([A-Z])/g, function(m) {
                    return m[0] + "_" + m[1];
                }).toLowerCase()
            },
            titleCase(str) {
                var splitStr = str.toLowerCase().split(' ');
                for (var i = 0; i < splitStr.length; i++) {
                    splitStr[i] = splitStr[i].charAt(0).toUpperCase() + splitStr[i].substring(1);
                }
                return splitStr.join(' ');
            },
            generate(){
                let data = this.data;
                if(data.modul.trim() == ''){
                    alert('Masukkan data dengan benar')
                }
                axios.post('/generate/'+this.modul.id, data).then(function(response){
                    console.log(response.data)
                }).catch(error=>{
                    console.log(error)
                })
            },
            rulesChange(e, rules){

            },
            bukanPrimary(tipe){
                return ![
                'bigIncrements',
                'increments',
                'tinyIncrements',
                ].includes(tipe)
            },
            isUnique(kolom){
                if(kolom.unique){
                    kolom.rules.push('unique:'+this.unique)
                }else{
                    kolom.rules.remove('unique:'+this.unique)
                }
            },
            isNullable(kolom){
                if(kolom.nullable){
                    kolom.rules.push('nullable')
                }else{
                    kolom.rules.remove('nullable')
                }
            },
            perbaruiUnik(kolom, unik = true){
                let indexArr = [];
                let teks = 'unique'
                if(!unik)
                    teks = 'exists'
                kolom.rules.forEach((item, index)=>{
                    if(item.includes(teks)){
                        indexArr.push(index)
                    }
                })
                indexArr.forEach((item, index)=>{
                    kolom.rules.splice(item, 1);
                })
            },
            setNama(kolom){
                let nama_asli = this.titleCase(kolom.nama_asli.replace(/ /g,'_'))
                kolom.nama = this.camelToSnake(nama_asli)
                kolom.tabel_kolom = this.data.tabel+','+kolom.nama
            },
            tambahKolom(kolom){
                kolom.push({
                    nama : '',
                    tipe : 'string',
                    panjang : null,
                    unique : false,
                    rules : [],
                    tabel_kolom : this.tabel+',kolom',
                    kolom_bootstrap : 'col-md-6',
                    sembunyikan : false,
                    nullable : false,
                })
            },
            sisipkanKolom([index, kolom]){
                kolom.splice(index+1, 0, {
                    nama : '',
                    tipe : 'string',
                    panjang : null,
                    unique : false,
                    rules : [],
                    tabel_kolom : this.tabel+',kolom',
                    kolom_bootstrap : 'col-md-6',
                    sembunyikan : false,
                    nullable : false,
                })
            },
            resetUniqueExists(kolom){
                kolom.tabel_kolom = this.data.tabel+','+kolom.nama
                let kolomBaru = [];
                this.data.kolom.forEach((item)=>{
                    let rulesBaru = []
                    if(typeof item.rules == 'object'){
                        item.rules.forEach((item2, index)=>{
                            if(item2.includes('unique')){
                                item.rules.splice(index, 1);
                            }
                            if(item2.includes('exists')){
                                item.rules.splice(index, 1);
                            }
                        })
                    }
                    kolomBaru.push(item)
                })
                this.data.kolom = kolomBaru
                this.data.grup.forEach((grupItem)=>{
                    this.data.kolom.forEach((item)=>{
                        if(typeof item.rules == 'object'){
                            item.rules.forEach((item2, index)=>{
                                if(item2.includes('unique')){
                                    item.rules.splice(index, 1);
                                }
                                if(item2.includes('exists')){
                                    item.rules.splice(index, 1);
                                }
                            })
                        }
                    })
                })
            },
            onChangeTipe(kolom){
                if(kolom.tipe == 'jenisKelamin'){
                    kolom.faker = 'jenisKelamin'
                    kolom.panjang = 50
                }
                if(kolom.tipe == 'alamat')
                    kolom.faker = 'address'
                if(kolom.tipe == 'email'){
                    kolom.faker = 'email'
                    kolom.panjang = 100
                    kolom.ikon = 'fas fa-envelope'
                }
                if(kolom.tipe == 'noHP'){
                    kolom.faker = 'phoneNumber'
                    kolom.panjang = 50
                    kolom.ikon = 'fas fa-phone'
                }
                let numericArray = ['double', 'bigInteger', 'integer', 'tinyInteger']
                if(numericArray.includes(kolom.tipe)){
                    kolom.ikon = ''
                    kolom.faker = 'digit'
                    kolom.panjang = ''
                    if(!kolom.rules.includes('numeric'))
                        kolom.rules.push('numeric')
                }else{
                    kolom.faker = ''
                    kolom.panjang = ''
                    kolom.rules.remove('numeric')
                }
            },
            sembunyikan(kolom){
                kolom.sembunyikan = true
            },
            perlihatkan(kolom){
                kolom.sembunyikan = false
            },
            tambahGrup(){
                this.data.grup.push(this.grup_template)
            },
            hapusmin(kolom){
                kolom.rules.forEach((item2, index)=>{
                    if(item2.includes('min')){
                        kolom.rules.splice(index, 1);
                    }
                })
            },
            hapusmax(kolom){
                kolom.rules.forEach((item2, index)=>{
                    if(item2.includes('max')){
                        kolom.rules.splice(index, 1);
                    }
                })
            },
            hapusNullable(kolom){
                if(kolom.rules.includes('required'))
                    kolom.rules.remove('nullable');
            },
            hapusRequired(kolom){
                if(kolom.rules.includes('nullable'))
                    kolom.rules.remove('required');
            },
            salin([koloms, kolom]){
                let kolomBaru = Object.assign({}, kolom)
                koloms.push(kolomBaru)
            },
            sisipkanGrup(index){
                this.data.grup.splice(index+1, 0, this.grup_template)
            },
            hapusGrup(indexGrup){
                this.data.grup.splice(indexGrup,1)
            },
            isForeignType(tipe){
                let foreignArray = ['bigIntegerForeign', 'integerForeign', 'tinyIntegerForeign']
                return foreignArray.includes(tipe)
            },
            kolomParent(modulParents, modulTerpilih){
                console.log(modulParents)
                console.log(modulTerpilih)
                let kolomModulLainnya = modulParents.filter((item)=>{
                    return item.id == modulTerpilih
                })
                if(kolomModulLainnya.length > 0)
                    return kolomModulLainnya[0].kolom
                return []
            }
        },
        props : {
            modul : {
                type : Object,
                required : true,
            },
            modulLainnya : {
                type : Array,
                required : true,
            },
        },
        components : {
            kolom,
        }
    }
</script>

<style scoped>
.barisku:nth-child(2){
    background-color: #9F9F9F;
}
</style>
