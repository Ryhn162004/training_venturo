import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DaftarDiskonComponent } from './daftar-diskon.component';

describe('DaftarDiskonComponent', () => {
  let component: DaftarDiskonComponent;
  let fixture: ComponentFixture<DaftarDiskonComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DaftarDiskonComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DaftarDiskonComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
