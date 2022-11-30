import { Component, OnInit } from '@angular/core';
import { ReportService } from '../service/report.service';
import { LandaService } from 'src/app/core/services/landa.service';

@Component({
  selector: 'app-rekap-menu',
  templateUrl: './rekap-menu.component.html',
  styleUrls: ['./rekap-menu.component.scss']
})
export class RekapMenuComponent implements OnInit {

  filter: {
    kategori? :string,
    id_bulan? :number,
    id_tahun? : number,
  }
  constructor(
    private reportService:ReportService,
    private landaService:LandaService
  ) {
    this.filter = {
        kategori : 'food',
        id_bulan : 8,
        id_tahun : 2022,
    }
   }

  ngOnInit(): void {
    this.getRekapMenu()
  }

  getRekapMenu(){
    const params = {
        filter: JSON.stringify(this.filter)
    }
    this.reportService.getRekapMenu(params).subscribe((res:any)=>{
        console.log(res.data);
    })
  }

}
