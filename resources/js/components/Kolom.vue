<template>
	<div :class="'row '+(indeks % 2 ? 'bg-biru' : 'bg-orange')">
		<template v-if="kolom.sembunyikan">
			<div class="col-md-12">
				<button class="btn btn-primary btn-sm" @click.prevent="perlihatkan(koloms[indeks])">Perlihatkan</button>
			</div>
		</template>
		<template v-else>
			<div class="col-sm-3">
				<div class="form-group">
					<label for="">Kolom {{ indeks+1 }} [view]</label>
					<input @keyup="setNama(koloms[indeks])" type="text" class="form-control" v-model="koloms[indeks].nama_asli">
				</div>
			</div>
			<div class="col-sm-3">
				<div class="form-group">
					<label for="">Kolom {{ indeks+1 }} [basisdata]</label>
					<input @keyup="resetUniqueExists(koloms[indeks])" type="text" class="form-control" v-model="koloms[indeks].nama">
				</div>
			</div>
			<div class="col-sm-3">
				<div class="form-group">
					<label for="">Tipe {{ indeks+1 }}</label>
					<select @change="onChangeTipe(koloms[indeks])" v-model="koloms[indeks].tipe" class="form-control">
						<option v-for="t in tipe" :value="t.value">{{ t.text }}</option>
					</select>
				</div>
			</div>
			<template v-if="isForeignType(koloms[indeks].tipe)">
				<div class="col-md-3">
					<div class="form-group">
						<label for="">Modul Parent</label>
						<select name="" id="" class="form-control" v-model="koloms[indeks].modul_parent">
							<option v-for="mm in modulLainnya" :value="mm.id">{{ mm.nama }}</option>
						</select>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label for="">Pilih Kolom Primary</label>
						<select name="" id="" class="form-control" v-model="koloms[indeks].kolom_parent">
							<option v-for="km in kolomParent(modulLainnya, koloms[indeks].modul_parent)" :value="km.nama">{{ km.nama }}</option>
						</select>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label for="">Pilih Kolom View (Select)</label>
						<select name="" id="" class="form-control" v-model="koloms[indeks].kolom_view">
							<option v-for="km in kolomParent(modulLainnya, koloms[indeks].modul_parent)" :value="km.nama">{{ km.nama }}</option>
						</select>
					</div>
				</div>
			</template>
			<template v-else-if="bukanPrimary(koloms[indeks].tipe)">
				<div class="col-sm-2">
					<div class="form-group">
						<label for="">Panjang {{ indeks+1 }}</label>
						<input type="number" min="0" class="form-control" v-model="koloms[indeks].panjang">
					</div>
				</div>
				<div class="col-sm-2">
					<input @change="isUnique(koloms[indeks])" class="mt-5" type="checkbox" v-model="koloms[indeks].unique"> Unique
					<input @change="isNullable(koloms[indeks])" class="mt-5" type="checkbox" v-model="koloms[indeks].nullable"> Nullable
				</div>
				<div class="col-sm-2">
					<div class="form-group">
						<label for="">Faker {{ indeks+1 }}</label>
						<select v-model="koloms[indeks].faker" class="form-control">
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
						<label for="">Ikon {{ indeks+1 }}</label>
						<input type="text" class="form-control" v-model="koloms[indeks].ikon">
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group">
						<label for="">Lebar Form {{ indeks+1 }}</label>
						<select class="form-control" v-model="koloms[indeks].kolom_bootstrap">
							<option v-for="i in 12 " :value="'col-md-'+i">{{ 'col-md-'+i }}</option>
							<option v-for="i in 12 " :value="'col-sm-'+i">{{ 'col-sm-'+i }}</option>
							<option v-for="i in 12 " :value="'col-lg-'+i">{{ 'col-lg-'+i }}</option>
							<option v-for="i in 12 " :value="'col-xs-'+i">{{ 'col-xs-'+i }}</option>
						</select>
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group">
						<label for="">Jenis Form {{ indeks+1 }}</label>
						<select class="form-control" v-model="koloms[indeks].jenis_form">
							<option value="input">input [text]</option>
							<option value="inputnumber">input [number]</option>
							<option value="inputimage">input [image]</option>
							<option value="select">select</option>
							<option value="datepicker">datepicker</option>
						</select>
					</div>
				</div>
				<div class="col-md-12" align="center">
					<h4 align="center">Validasi kolom {{ indeks+1 }}</h4>
					<input @change="hapusNullable(koloms[indeks])" type="checkbox" v-model="koloms[indeks].rules" value="required"> required
					<input @change="hapusRequired(koloms[indeks])" type="checkbox" v-model="koloms[indeks].rules" value="nullable"> nullable
					<input type="checkbox" v-model="koloms[indeks].rules" value="email"> email
					<input type="checkbox" v-model="koloms[indeks].rules" value="string"> string
					<input type="checkbox" v-model="koloms[indeks].rules" value="numeric"> numeric
					<input type="checkbox" v-model="koloms[indeks].rules" value="file"> file
					<input type="checkbox" v-model="koloms[indeks].rules" value="mimes:jpeg,png"> image
					<input type="checkbox" v-model="koloms[indeks].rules" value="date_format:Y-m-d"> date_format:Y-m-d
					<br>

					<input type="checkbox" v-model="koloms[indeks].rules" :value="'unique:'+koloms[indeks].tabel_kolom"> unique
					<input type="text" v-model="koloms[indeks].tabel_kolom" @keyup="perbaruiUnik(koloms[indeks])" @change="perbaruiUnik(koloms[indeks])">

					<input type="checkbox" v-model="koloms[indeks].rules" :value="'exists:'+koloms[indeks].tabel_kolom"> exists
					<input type="text" v-model="koloms[indeks].tabel_kolom" @keyup="perbaruiUnik(koloms[indeks], false)" @change="perbaruiUnik(koloms[indeks], false)">

					<input type="checkbox" v-model="koloms[indeks].rules" :value="'min:'+koloms[indeks].min"> min
					<input @keydown="hapusmin(koloms[indeks])" type="number" min="0" v-model="koloms[indeks].min">

					<input type="checkbox" v-model="koloms[indeks].rules" :value="'max:'+koloms[indeks].max"> max
					<input @keydown="hapusmax(koloms[indeks])" type="number" min="0" v-model="koloms[indeks].max">

				</div>
			</template>
			<div class="col-md-12" align="center">
				<br>
				<button @click.prevent="koloms.splice(indeks,1)" class="btn btn-danger btn-sm">Hapus</button>
				<button @click.prevent="$emit('sisipkanKolom', [indeks, kolom])" class="btn btn-success btn-sm">Sisipkan Kolom</button>
				<button @click.prevent="$emit('sembunyikan', koloms[indeks])" class="btn btn-primary btn-sm">Sembunyikan</button>
				<button @click.prevent="$emit('salin', [koloms, koloms[indeks]])" class="btn btn-info btn-sm">Salin</button>
				<hr>
			</div>
		</template>
	</div>
</template>
<script>
	import TIPE from './Tipe.vue'
	export default {
		data(){
			return {
				tipe : TIPE,
			}
		},
		props : {
			kolom : {
				type : Object,
				required : true,
			},
			koloms : {
				type : Array,
				required : true,
			},
			indeks : {
				type : Number,
				required : true,
			},
			modulLainnya : {

			},
			tabel : {
				
			}
		},
		methods : {
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
			},
            setNama(kolom){
                let nama_asli = this.titleCase(kolom.nama_asli.replace(/ /g,'_'))
                kolom.nama = this.camelToSnake(nama_asli)
                kolom.tabel_kolom = this.tabel+','+kolom.nama
            },
            titleCase(str) {
                var splitStr = str.toLowerCase().split(' ');
                for (var i = 0; i < splitStr.length; i++) {
                    splitStr[i] = splitStr[i].charAt(0).toUpperCase() + splitStr[i].substring(1);
                }
                return splitStr.join(' ');
            },
            camelToSnake(string) {
                return string.replace(/[\w]([A-Z])/g, function(m) {
                    return m[0] + "_" + m[1];
                }).toLowerCase()
            },
		}
	}
</script>