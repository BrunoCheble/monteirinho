<!-- FullCalendar -->
<link href="<?= base_url(); ?>assets/fullcalendar/dist/fullcalendar.min.css" rel="stylesheet">
<link href="<?= base_url(); ?>assets/fullcalendar/dist/fullcalendar.print.css" rel="stylesheet" media="print">
<style>
    .fc {
        border: 0;
    }
    .fc-view { background:#fff;}

    .fc-time-grid .fc-slats td {
        height: 2.5em;
        text-align: center;
        font-weight: bold;
    }
</style>

<!-- Modal -->
<div id="modal-excluir" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form action="<?php echo base_url() ?>index.php/agendamentos/excluir" method="post">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h5 id="myModalLabel">Excluir Agendamento</h5>
        </div>
        <div class="modal-body">
            <input type="hidden" id="idAgendamento" name="id" value="" />
            <h5 style="text-align: center">Deseja realmente excluir este agendamento ?</h5>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
            <button class="btn btn-danger">Excluir</button>
        </div>
    </form>
</div>

<!-- Modal Filtro lançamento-->
<div id="modalAgendamento" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      <h3 id="myModalLabel">Detalhes do Agendamento</h3>
    </div>
    <div class="modal-body">
        <h4 id="titulo"></h4>
        <p id="descricao"></p>
    </div>
    <div class="modal-footer">
      <button class="btn pull-left" data-dismiss="modal" aria-hidden="true">Fechar</button>
      <a href="#modal-excluir" data-dismiss="modal" role="button" data-toggle="modal" class="btn btn-danger" title="" data-original-title="Excluir Agendamento"><i class="fas fa-trash-alt"></i> Excluir</a>

      <a href="" id="btnVenda" class="btn btn-primary"><i class="fas fa-store"></i> Detalhes da Venda</a>
      <a href="" id="btnEditar" class="btn btn-warning"><i class="fas fa-edit"></i> Editar Agendamento</a>
    </div>
</div>

<div style="margin-top: 10px;" id="calendar"></div>

<!-- FullCalendar -->
<script src="<?= base_url(); ?>assets/fullcalendar/dist/fullcalendar.min.js"></script>
<script src="<?= base_url(); ?>assets/fullcalendar/dist/lang/pt.js"></script>

<script type="text/javascript">
        var base_url = '<?= base_url(); ?>';
        $(document).ready(function() {
            $calendar = $('#calendar');

            $request_events = {
                url: base_url + 'index.php/agendamentos/ajax_agendamentos',
                type: 'POST',
                data: {term: $('#search').val()},
                error: function (xhr, status, error) {
                    if (xhr.responseText != undefined) {
                        //alert('Error');
                    }
                }
            };

            var view_default = "month";

            function refresh_calendar() {
                $request_events.data = {term: $('#search').val()};

                $calendar.fullCalendar('removeEventSource', $request_events);
                //$request_events.data.term = $('#search').val();
                $calendar.fullCalendar('addEventSource', $request_events);
            }

            function change_event_datetime(event) {
                $.ajax({
                    type: 'POST',
                    url: base_url + 'index.php/agendamentos/ajax_atualiza_data_agendamento',
                    data: {
                        event_id: event.id,
                        table: event.table,
                        start: event.start.format(),
                        end: event.end.format()
                    }, success: function (e) {
                        $calendar.fullCalendar('refetchEvents');
                    },
                    complete: function (e) {}
                });
            }

            function init_calendar() {

                $calendar.fullCalendar({
                    views: {
                        week: {columnFormat: 'ddd D/M'}
                    },
                    buttonText: {
                        today:    'Hoje',
                        month:    'Mês',
                        week:     'Semana',
                        day:      'Dia'
                    },
                    eventTextColor: '#fff',
                    allDaySlot : false,
                    slotLabelFormat: 'H' ,
                    allDayText: 'Dia todo',
                    slotDuration: '01:00:00',
                    customButtons: {
                        AddAgendamentoBtn: {
                            text: 'Novo Agendamento',
                            click: function () {
                                window.location.href = '<?php echo base_url() ?>index.php/agendamentos/adicionar';
                            }
                        }
                    },
                    header: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'Label agendaWeek,month, AddAgendamentoBtn'
                    },
                    displayEventTime: false,
                    editable: true,
                    dayClick: function(date, jsEvent, view) {
                        
                    },
                    eventRender: function (event, element) {
                        if (event.resizable == 0) {
                            event.durationEditable = false;
                        }
                        if (event.draggable == 0) {
                            event.startEditable = false;
                        }
                        return element;
                    },
                    eventDrop: function (event, dayDelta, minuteDelta, allDay, revertFunc, jsEvent, ui, view) {
                        change_event_datetime(event);
                        $('.popover').remove();
                    },
                    droppable: true,
                    locale: 'pt',
                    defaultView: 'agendaWeek',
                    firstDay: '1',
                    height: 'auto',
                    minTime: '01:00:00',
                    maxTime: '23:00:00',
                    drop: function (date, jsEvent, ui, resourceId) {

                    },
                    eventAfterAllRender: function (view) {
                        var height = view.name === 'month' ? 700 : 'auto';
                        $calendar.fullCalendar('option', 'height', height);
                    },
                    events: $request_events,
                    eventClick: function (event) {
                        $('#modalAgendamento #btnEditar').attr('href',base_url+'/index.php/agendamentos/editar/'+event.id);
                        $('#modalAgendamento #btnVenda').attr('href',base_url+'/index.php/vendas/visualizar/'+event.vendas_id);
                        $('#modalAgendamento #titulo').text(event.title);
                        $('#modalAgendamento #descricao').text(event.description);
                        $('#idAgendamento').val(event.id);
                        $('#modalAgendamento').modal('show');
                    }
                });
            }

            init_calendar();
    });
</script>
