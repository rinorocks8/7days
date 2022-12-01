import React, { useState, useRef } from 'react';
import { Scrollbars } from 'react-custom-scrollbars-2';

import { Controls } from './Controls/Controls';
import { Header } from './Header/Header';
import { Row } from './Row/Row';
import { convertDate } from 'libs/date';

import { ViewportList } from 'react-viewport-list';

export const EventList = ({events, settings, getEvents, className}) => {

  const [filterCompleted, setFilterCompleted] = useState(true);

  const extraProps = settings.extraProperties;
  let filtered_events = events.filter((event) => 
    !(filterCompleted && JSON.parse(event.extendedProperties?.private[event.recurringEventId ? event.recurringEventId : "single"] || "{}")?.completed === true)
  );

  let events_grouped = groupBy(filtered_events, event => convertDate(event.convertedEnd).clumped_date)

  const toggleFilterCompleted = () => {
    setFilterCompleted(!filterCompleted);
  }

  const ref = useRef(null);
  const listRef = useRef([]);

  return (
    <div className={'w-full flex flex-1 flex-col overflow-y-hidden ' + className}>
      <div className="sticky top-0 z-50">
        <Controls toggleFilterCompleted={toggleFilterCompleted} listRef={listRef} events_grouped={events_grouped}/>
      </div>
        <div className='w-full flex-1' ref={ref}>
          <Scrollbars
            className='w-full' 
            renderThumbVertical={()=>
              <div 
                style={{	        
                  cursor: 'pointer',
                  borderRadius: 'inherit',
                  backgroundColor: 'rgba(0,0,0,.2)',
                  zIndex: 100,
                }}
              />
            }
            renderTrackVertical={()=>
              <div 
                style={{
                  position: "absolute",
                  width: 6,
                  right: 2,
                  bottom: 2,
                  top: 2,
                  borderRadius: 3,
                  zIndex: 100,
                }}
              />
            }
          >
            {events_grouped.map(([key, value], index) => {
              return (
                <div key={key} ref={el => listRef.current[index] = el}>
                  <div className="sticky top-0 z-40">
                    <Header data={key} extraProps={extraProps}/>
                  </div>
                  <ViewportList
                    viewportRef={ref}
                    items={value}
                  >
                    {(item) => (
                      <Row
                        key={item.id} 
                        data={item} 
                        extraProps={extraProps} 
                        getEvents={getEvents}
                      />
                    )}
                  </ViewportList>
                </div>
              )
            })}
          </Scrollbars>
        </div>
    </div>
  );
}

function groupBy(list, keyGetter) {
  let output = [];
  list.forEach((item) => {
    let key = keyGetter(item);
    if (output.length > 0 && output[output.length-1][0] === key) {
      output[output.length-1][1].push(item)
    } else {
      output.push([key, [item]])
    }
  });
  return output;
}