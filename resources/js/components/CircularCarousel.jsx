import React, { useRef, useState, Children, forwardRef, useImperativeHandle } from 'react';
import './CircularCarousel.css';

export function lerp(start, stop, amt) {
  return (1 - amt) * start + amt * stop;
}

const ANGLE_PER_ITEM = 100;
const VISIBLE_ITEMS = 3;
const BUFFER_ITEMS = 2;
const CENTER_OFFSET = 0;

function CircularCarouselComp(
  { onSelect, onSwapRight, onPointerDown, children },
  ref
) {
  const childArray = Children.toArray(children);
  const len = childArray.length;
  const indexRef = useRef(0);
  const prevRef = useRef(0);
  const nextRef = useRef(0);
  const rendering = useRef(false);
  const [deg, setDeg] = useState(CENTER_OFFSET);
  const [wrapper, setWrapper] = useState(null);
  const autoSlideInterval = useRef(null);

  const handleSetWrapper = (ref) => {
    setWrapper(ref);
  };

  prevRef.current = deg;

  function move() {
    if (len === 0) {
      return;
    }

    const next = nextRef.current;
    const prev = prevRef.current;
    const deg = lerp(prev, next, 0.15);
    if (deg !== prev) {
      setDeg(deg);
      requestAnimationFrame(move);
    } else {
      rendering.current = false;
    }
    
    const totalArc = ANGLE_PER_ITEM * len;
    const normalized = ((-(deg - CENTER_OFFSET) % totalArc) + totalArc) % totalArc;
    const index = Math.round(normalized / ANGLE_PER_ITEM) % len;
    if (index !== indexRef.current) {
      indexRef.current = index;
      onSelect && onSelect(index);
    }
  }

  const onMouseDown = (e) => {
    const isTouch = e.type === "touchstart";
    let _deg = deg;

    onPointerDown && onPointerDown();

    const tryMove = (next) => {
      _deg = nextRef.current += next;
      if (!rendering.current) {
        rendering.current = true;
        requestAnimationFrame(move);
      }
    };

    const onMouseMove = ({ movementX }) => {
      tryMove(movementX / 30);
    };

    let prevTouchPageX;
    const onTouchMove = ({ touches }) => {
      const pageX = touches[0].pageX;
      if (prevTouchPageX) {
        const movementX = pageX - prevTouchPageX;
        tryMove(movementX / 10);
      }
      prevTouchPageX = pageX;
    };

  const onMouseUp = () => {
    document.removeEventListener("touchmove", onTouchMove);
    document.removeEventListener("mousemove", onMouseMove);
    document.removeEventListener("mouseup", onMouseUp);
    document.removeEventListener("touchend", onMouseUp);

    if (len === 0) {
      return;
    }

    nextRef.current = _deg;
    if (!rendering.current) {
      rendering.current = true;
      requestAnimationFrame(move);
    }
  };
    if (isTouch) {
      document.addEventListener("touchmove", onTouchMove);
      document.addEventListener("touchend", onMouseUp);
    } else {
      document.addEventListener("mousemove", onMouseMove);
      document.addEventListener("mouseup", onMouseUp);
    }
  };

  React.useEffect(() => {
    // No wheel event listener - scroll is disabled
    return () => {
      // Cleanup if needed
    };
  }, []);


  useImperativeHandle(
    ref,
    () => ({
      scrollTo(i) {
        if (len === 0) {
          return;
        }
        
        const totalArc = ANGLE_PER_ITEM * len;
        const currentNormalized = ((-(deg - CENTER_OFFSET) % totalArc) + totalArc) % totalArc;
        const currentIndex = Math.round(currentNormalized / ANGLE_PER_ITEM) % len;
        
        let targetIndex = i % len;
        if (targetIndex < 0) {
          targetIndex += len;
        }
        
        let indexDiff = targetIndex - currentIndex;
        
        if (indexDiff === 0) {
          return;
        }
        
        if (indexDiff < 0) {
          indexDiff += len;
        }
        
        if (indexDiff > 1) {
          indexDiff = 1;
        }
        
        const degDiff = -indexDiff * ANGLE_PER_ITEM;
        nextRef.current = deg + degDiff;
        
        if (!rendering.current) {
          rendering.current = true;
          requestAnimationFrame(move);
        }
      },
    }),
    [len, deg],
  );

  const renderItems = () => {
    if (len === 0) {
      return null;
    }

    const items = [];
    const totalCopies = 3;

    for (let copy = -1; copy <= totalCopies; copy++) {
      childArray.forEach((child, i) => {
        const absoluteIndex = copy * len + i;
        const angle = absoluteIndex * ANGLE_PER_ITEM;
        
        items.push(
          <div
            key={`${copy}-${i}`}
            className="circular-carousel-item"
            style={{
              transform: `translateX(-50%) rotate(${angle}deg)`,
            }}
          >
            {child}
          </div>
        );
      });
    }

    return items;
  };

  return (
    <div className="circular-carousel-root" ref={handleSetWrapper}>
      <div
        className="circular-carousel-handle"
        onMouseDown={onMouseDown}
        onTouchStart={onMouseDown}
      >
        <div className="circular-carousel-center">
          <div
            className="circular-carousel-items"
            style={{ transform: `rotate(${deg}deg)` }}
          >
            {renderItems()}
          </div>
        </div>
      </div>
    </div>
  );
}

const CircularCarousel = forwardRef(CircularCarouselComp);

export default CircularCarousel;
