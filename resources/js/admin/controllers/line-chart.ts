import { Controller } from '@hotwired/stimulus';
import uPlot from 'uplot';
import 'uplot/dist/uPlot.min.css';

export default class extends Controller {
    static targets = ['table', 'chart', 'summary', 'legend', 'axis'];

    tableTarget?: HTMLTableElement;
    chartTarget?: HTMLElement;
    summaryTarget?: HTMLElement;
    legendTarget?: HTMLElement;
    axisTarget?: HTMLElement;

    observer?: ResizeObserver;
    uplot?: uPlot;

    private getSize() {
        return {
            width: this.chartTarget!.offsetWidth,
            height: this.chartTarget!.offsetHeight,
        };
    }

    connect() {
        this.tableTarget!.hidden = true;
        this.chartTarget!.hidden = false;
        this.axisTarget!.hidden = false;

        this.observer = new ResizeObserver(() => {
            this.uplot?.setSize(this.getSize());
        });

        this.observer.observe(this.chartTarget!);

        const options: uPlot.Options = {
            ...this.getSize(),
            padding: [1, 1, 1, 1],
            series: [
                {},
                {
                    stroke: '#ccc',
                    width: 2,
                    points: {
                        show: false,
                    },
                },
                {
                    stroke: getComputedStyle(document.documentElement).getPropertyValue('--color-accent'),
                    width: 2,
                    points: {
                        show: false,
                    },
                },
            ],
            cursor: {
                y: false,
            },
            axes: [
                { show: false },
                { show: false }
            ],
            scales: {
                y: {
                    range: (u, dataMin, dataMax) => [dataMin, dataMax],
                },
            },
            select: {
                show: false,
            } as uPlot.Select, // bug
            legend: {
                show: false,
            },
            hooks: {
                init: [
                    u => {
                        u.over.addEventListener('mouseenter', () => {
                            this.summaryTarget!.hidden = true;
                            this.legendTarget!.hidden = false;
                        });
                        u.over.addEventListener('mouseleave', () => {
                            this.summaryTarget!.hidden = false;
                            this.legendTarget!.hidden = true;
                        });
                    }
                ],
                setCursor: [
                    u => {
                        const { idx } = u.cursor;
                        this.legendTarget!.innerHTML = `
                            <div class="text-lg">${uPlot.fmtNum(u.data[2][idx!] || 0)}</div>
                            <div class="text-xs color-muted">${ths[idx!].textContent}</div>
                        `;
                    },
                ]
            },
        };

        const ths = Array.from(this.tableTarget!.querySelectorAll<HTMLElement>('thead th')).slice(1);

        const data: uPlot.AlignedData = [
            ths.map(th => Number(th.dataset.timestamp)),
            ...Array.from(this.tableTarget!.querySelectorAll('tbody tr'))
                .reverse()
                .map(tr => Array.from(tr.querySelectorAll('td')).map(td => Number(td.textContent))),
        ];

        this.uplot = new uPlot(options, data, this.chartTarget!);
    }

    disconnect() {
        this.observer?.disconnect();
        this.uplot?.destroy();
    }
}
