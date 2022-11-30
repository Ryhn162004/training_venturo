import { Injectable } from '@angular/core';
import { LandaService } from 'src/app/core/services/landa.service';

@Injectable({
  providedIn: 'root'
})
export class ReportService {

  constructor(
    private landaService: LandaService
  ) { }

  getAllRekap(arrParameter){
    return this.landaService.DataGet('/v1/report', arrParameter)
  }

  getRekapMenu(arrParameter){
    console.log(arrParameter);
    return this.landaService.DataGet('/v1/report_menu', arrParameter);
  }
}
